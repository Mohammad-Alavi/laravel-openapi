<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ArraySchema;

use Illuminate\Http\Resources\Json\JsonResource;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

use function Safe\file_get_contents;

final readonly class ArraySchemaAnalyzer
{
    private const CONDITIONAL_METHODS = [
        'when',
        'unless',
        'whenLoaded',
        'whenHas',
        'whenNotNull',
        'whenNull',
        'whenAppended',
        'whenCounted',
        'whenAggregated',
        'whenExistsLoaded',
        'whenPivotLoaded',
        'whenPivotLoadedAs',
    ];

    /**
     * Analyze a class method that returns an array and extract field information.
     *
     * @param class-string $className
     *
     * @return ArrayField[]
     */
    public function analyzeMethod(string $className, string $methodName): array
    {
        $reflection = new \ReflectionMethod($className, $methodName);
        $fileName = $reflection->getFileName();

        if (false === $fileName) {
            return [];
        }

        $source = file_get_contents($fileName);

        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        $ast = $parser->parse($source);

        if (null === $ast) {
            return [];
        }

        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        if (false === $startLine || false === $endLine) {
            return [];
        }

        $arrayItems = $this->extractReturnArrayItems($ast, $startLine, $endLine);

        return $this->processArrayItems($arrayItems, $className);
    }

    /**
     * @param Node\Stmt[] $ast
     *
     * @return Expr\ArrayItem[]
     */
    private function extractReturnArrayItems(array $ast, int $startLine, int $endLine): array
    {
        $visitor = new class($startLine, $endLine) extends NodeVisitorAbstract {
            /** @var Expr\ArrayItem[] */
            public array $items = [];

            public function __construct(
                private readonly int $startLine,
                private readonly int $endLine,
            ) {
            }

            public function enterNode(Node $node): int|null
            {
                if (
                    $node instanceof Node\Stmt\Return_
                    && $node->getStartLine() >= $this->startLine
                    && $node->getEndLine() <= $this->endLine
                    && $node->expr instanceof Expr\Array_
                ) {
                    foreach ($node->expr->items as $item) {
                        $this->items[] = $item;
                    }

                    return NodeTraverser::STOP_TRAVERSAL;
                }

                return null;
            }
        };

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);
        $traverser->traverse($ast);

        return $visitor->items;
    }

    /**
     * @param Expr\ArrayItem[] $items
     * @param class-string $contextClass
     *
     * @return ArrayField[]
     */
    private function processArrayItems(array $items, string $contextClass): array
    {
        $fields = [];

        foreach ($items as $item) {
            if (null === $item->key) {
                $mergedFields = $this->extractMergeFields($item->value, $contextClass);
                array_push($fields, ...$mergedFields);

                continue;
            }

            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }

            $name = $item->key->value;
            $fields[] = $this->classifyExpression($name, $item->value, $contextClass);
        }

        return $fields;
    }

    /**
     * @param class-string $contextClass
     */
    private function classifyExpression(string $name, Expr $expr, string $contextClass): ArrayField
    {
        // $this->property
        if ($expr instanceof Expr\PropertyFetch && $this->isThisExpr($expr->var)) {
            $property = $expr->name instanceof Node\Identifier ? $expr->name->name : $name;

            return ArrayField::modelProperty($name, $property);
        }

        // String/Int/Float literal
        if ($expr instanceof Node\Scalar\String_) {
            return ArrayField::literal($name, $expr->value);
        }

        if ($expr instanceof Node\Scalar\Int_) {
            return ArrayField::literal($name, $expr->value);
        }

        if ($expr instanceof Node\Scalar\Float_) {
            return ArrayField::literal($name, $expr->value);
        }

        // Boolean and null literals: true, false, null
        if ($expr instanceof Expr\ConstFetch) {
            $constName = $expr->name->toLowerString();

            if ('true' === $constName) {
                return ArrayField::literal($name, true);
            }

            if ('false' === $constName) {
                return ArrayField::literal($name, false);
            }

            if ('null' === $constName) {
                return ArrayField::literal($name, null);
            }
        }

        // $this->prop->method(...) — method chain on model property
        if (
            $expr instanceof Expr\MethodCall
            && $expr->var instanceof Expr\PropertyFetch
            && $this->isThisExpr($expr->var->var)
        ) {
            $property = $expr->var->name instanceof Node\Identifier ? $expr->var->name->name : $name;

            return ArrayField::modelProperty($name, $property);
        }

        // $this->resource->prop — explicit resource access
        if (
            $expr instanceof Expr\PropertyFetch
            && $expr->var instanceof Expr\PropertyFetch
            && $this->isThisExpr($expr->var->var)
            && $expr->var->name instanceof Node\Identifier
            && 'resource' === $expr->var->name->name
        ) {
            $property = $expr->name instanceof Node\Identifier ? $expr->name->name : $name;

            return ArrayField::modelProperty($name, $property);
        }

        // new SomeClass($this->...)
        if ($expr instanceof Expr\New_ && $expr->class instanceof Node\Name) {
            $className = $this->resolveClassName($expr->class, $contextClass);

            if (null !== $className) {
                return ArrayField::relationship($name, $className);
            }
        }

        // SomeClass::collection($this->items)
        if (
            $expr instanceof Expr\StaticCall
            && $expr->class instanceof Node\Name
            && $expr->name instanceof Node\Identifier
            && 'collection' === $expr->name->name
        ) {
            $className = $this->resolveClassName($expr->class, $contextClass);

            if (null !== $className) {
                return ArrayField::collection($name, $className);
            }
        }

        // $this->when*(...) / $this->unless(...)
        if ($expr instanceof Expr\MethodCall && $this->isThisExpr($expr->var)) {
            $methodName = $expr->name instanceof Node\Identifier ? $expr->name->name : '';

            if (in_array($methodName, self::CONDITIONAL_METHODS, true)) {
                return ArrayField::conditional($name);
            }
        }

        return ArrayField::unknown($name);
    }

    /**
     * @param class-string $contextClass
     *
     * @return ArrayField[]
     */
    private function extractMergeFields(Expr $expr, string $contextClass): array
    {
        if (!$expr instanceof Expr\MethodCall || !$this->isThisExpr($expr->var)) {
            return [];
        }

        $methodName = $expr->name instanceof Node\Identifier ? $expr->name->name : '';

        // merge([...]): first argument is the array
        if ('merge' === $methodName && isset($expr->args[0]) && $expr->args[0] instanceof Node\Arg) {
            $arrayExpr = $expr->args[0]->value;

            if ($arrayExpr instanceof Expr\Array_) {
                return $this->processArrayItems($arrayExpr->items, $contextClass);
            }
        }

        // mergeWhen($cond, [...])/mergeUnless($cond, [...]): second argument is the array
        if (('mergeWhen' === $methodName || 'mergeUnless' === $methodName)
            && isset($expr->args[1])
            && $expr->args[1] instanceof Node\Arg
        ) {
            $arrayExpr = $expr->args[1]->value;

            if ($arrayExpr instanceof Expr\Array_) {
                return $this->processArrayItems($arrayExpr->items, $contextClass);
            }
        }

        return [];
    }

    /**
     * @param class-string $contextClass
     *
     * @return class-string|null
     */
    private function resolveClassName(Node\Name $className, string $contextClass): string|null
    {
        $name = $className->toString();

        if (!class_exists($name)) {
            $namespace = (new \ReflectionClass($contextClass))->getNamespaceName();
            $fqcn = $namespace . '\\' . $name;

            if (class_exists($fqcn) && is_subclass_of($fqcn, JsonResource::class)) {
                return $fqcn;
            }
        }

        if (class_exists($name) && is_subclass_of($name, JsonResource::class)) {
            return $name;
        }

        return null;
    }

    private function isThisExpr(Expr $expr): bool
    {
        return $expr instanceof Expr\Variable && 'this' === $expr->name;
    }
}
