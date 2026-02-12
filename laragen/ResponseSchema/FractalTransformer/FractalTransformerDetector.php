<?php

namespace MohammadAlavi\Laragen\ResponseSchema\FractalTransformer;

use MohammadAlavi\Laragen\ResponseSchema\ResponseDetector;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

use function Safe\file_get_contents;

final readonly class FractalTransformerDetector implements ResponseDetector
{
    private const TRANSFORMER_ABSTRACT = 'League\Fractal\TransformerAbstract';

    /**
     * @param class-string $controllerClass
     *
     * @return class-string|null
     */
    public function detect(string $controllerClass, string $method): mixed
    {
        if (!class_exists(self::TRANSFORMER_ABSTRACT)) {
            return null;
        }

        if (!method_exists($controllerClass, $method)) {
            return null;
        }

        $reflection = new \ReflectionMethod($controllerClass, $method);
        $fileName = $reflection->getFileName();

        if (false === $fileName) {
            return null;
        }

        $source = file_get_contents($fileName);
        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        $ast = $parser->parse($source);

        if (null === $ast) {
            return null;
        }

        $startLine = $reflection->getStartLine();
        $endLine = $reflection->getEndLine();

        if (false === $startLine || false === $endLine) {
            return null;
        }

        return $this->findTransformerInMethodBody($ast, $startLine, $endLine, $controllerClass);
    }

    /**
     * @param Node\Stmt[] $ast
     * @param class-string $contextClass
     *
     * @return class-string|null
     */
    private function findTransformerInMethodBody(array $ast, int $startLine, int $endLine, string $contextClass): string|null
    {
        $visitor = new class($startLine, $endLine) extends NodeVisitorAbstract {
            /** @var list<string> */
            public array $candidates = [];

            /** @var array<string, string> short name → FQCN from use statements */
            public array $useMap = [];

            public function __construct(
                private readonly int $startLine,
                private readonly int $endLine,
            ) {
            }

            public function enterNode(Node $node): int|null
            {
                // Collect use imports (these are outside method boundaries)
                if ($node instanceof Node\Stmt\Use_) {
                    foreach ($node->uses as $use) {
                        $fqcn = $use->name->toString();
                        $alias = !is_null($use->alias) ? $use->alias->name : $use->name->getLast();
                        $this->useMap[$alias] = $fqcn;
                    }

                    return null;
                }

                if ($node->getStartLine() < $this->startLine || $node->getEndLine() > $this->endLine) {
                    return null;
                }

                // new BookTransformer()
                if ($node instanceof Expr\New_ && $node->class instanceof Node\Name) {
                    $this->candidates[] = $node->class->toString();
                }

                // BookTransformer::class
                if (
                    $node instanceof Expr\ClassConstFetch
                    && $node->class instanceof Node\Name
                    && $node->name instanceof Node\Identifier
                    && 'class' === $node->name->name
                ) {
                    $this->candidates[] = $node->class->toString();
                }

                return null;
            }
        };

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);
        $traverser->traverse($ast);

        $namespace = (new \ReflectionClass($contextClass))->getNamespaceName();

        foreach ($visitor->candidates as $candidate) {
            $fqcn = $this->resolveToTransformer($candidate, $namespace, $visitor->useMap);

            if (null !== $fqcn) {
                return $fqcn;
            }
        }

        return null;
    }

    /**
     * @param array<string, string> $useMap
     *
     * @return class-string|null
     */
    private function resolveToTransformer(string $className, string $namespace, array $useMap): string|null
    {
        // Already fully qualified
        if (class_exists($className) && is_subclass_of($className, self::TRANSFORMER_ABSTRACT)) {
            return $className;
        }

        // Resolve via use imports
        if (isset($useMap[$className])) {
            $imported = $useMap[$className];

            if (class_exists($imported) && is_subclass_of($imported, self::TRANSFORMER_ABSTRACT)) {
                return $imported;
            }
        }

        // Resolve via same namespace
        $fqcn = $namespace . '\\' . $className;

        if (class_exists($fqcn) && is_subclass_of($fqcn, self::TRANSFORMER_ABSTRACT)) {
            return $fqcn;
        }

        return null;
    }
}
