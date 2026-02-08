<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema;

use Illuminate\Http\Resources\Json\JsonResource;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

use function Safe\file_get_contents;

final readonly class JsonResourceAnalyzer
{
    /**
     * Analyze a JsonResource class and extract field information from toArray().
     *
     * @param class-string<JsonResource> $resourceClass
     *
     * @return ResourceField[]
     */
    public function analyze(string $resourceClass): array
    {
        $reflection = new \ReflectionMethod($resourceClass, 'toArray');
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

        return $this->processArrayItems($arrayItems, $resourceClass);
    }

    /**
     * Get the wrap key for a resource class.
     *
     * @param class-string<JsonResource> $resourceClass
     */
    public function getWrapKey(string $resourceClass): string|null
    {
        $reflection = new \ReflectionClass($resourceClass);
        $wrapProperty = $reflection->getProperty('wrap');

        /** @var string|null $wrap */
        $wrap = $wrapProperty->getValue();

        return $wrap;
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
     * @param class-string<JsonResource> $resourceClass
     *
     * @return ResourceField[]
     */
    private function processArrayItems(array $items, string $resourceClass): array
    {
        $fields = [];

        foreach ($items as $item) {
            if (!$item->key instanceof Node\Scalar\String_) {
                continue;
            }

            $name = $item->key->value;
            $fields[] = $this->classifyExpression($name, $item->value, $resourceClass);
        }

        return $fields;
    }

    /**
     * @param class-string<JsonResource> $resourceClass
     */
    private function classifyExpression(string $name, Expr $expr, string $resourceClass): ResourceField
    {
        // $this->property
        if ($expr instanceof Expr\PropertyFetch && $this->isThisExpr($expr->var)) {
            $property = $expr->name instanceof Node\Identifier ? $expr->name->name : $name;

            return ResourceField::modelProperty($name, $property);
        }

        // String/Int/Float literal
        if ($expr instanceof Node\Scalar\String_) {
            return ResourceField::literal($name, $expr->value);
        }

        if ($expr instanceof Node\Scalar\Int_) {
            return ResourceField::literal($name, $expr->value);
        }

        if ($expr instanceof Node\Scalar\Float_) {
            return ResourceField::literal($name, $expr->value);
        }

        // new SomeResource($this->whenLoaded(...))
        if ($expr instanceof Expr\New_ && $this->isResourceClass($expr)) {
            $className = $this->resolveClassName($expr, $resourceClass);

            if (null !== $className) {
                return ResourceField::relationship($name, $className);
            }
        }

        // $this->when(...) or $this->whenLoaded(...)
        if ($expr instanceof Expr\MethodCall && $this->isThisExpr($expr->var)) {
            $methodName = $expr->name instanceof Node\Identifier ? $expr->name->name : '';

            if ('when' === $methodName || 'whenLoaded' === $methodName) {
                return ResourceField::conditional($name);
            }
        }

        return ResourceField::unknown($name);
    }

    private function isThisExpr(Expr $expr): bool
    {
        return $expr instanceof Expr\Variable && 'this' === $expr->name;
    }

    private function isResourceClass(Expr\New_ $expr): bool
    {
        return $expr->class instanceof Node\Name;
    }

    /**
     * @param class-string<JsonResource> $resourceClass
     *
     * @return class-string|null
     */
    private function resolveClassName(Expr\New_ $expr, string $resourceClass): string|null
    {
        if (!$expr->class instanceof Node\Name) {
            return null;
        }

        $className = $expr->class->toString();

        // Resolve relative class names using the resource class namespace
        if (!class_exists($className)) {
            $namespace = (new \ReflectionClass($resourceClass))->getNamespaceName();
            $fqcn = $namespace . '\\' . $className;

            if (class_exists($fqcn)) {
                return $fqcn;
            }
        }

        if (class_exists($className) && is_subclass_of($className, JsonResource::class)) {
            return $className;
        }

        return null;
    }
}
