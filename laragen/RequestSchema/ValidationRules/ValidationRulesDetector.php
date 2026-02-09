<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\ValidationRules;

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\RequestSchema\RequestDetector;
use MohammadAlavi\Laragen\Support\RuleExtractor;

final readonly class ValidationRulesDetector implements RequestDetector
{
    public function __construct(
        private RuleExtractor $ruleExtractor,
    ) {
    }

    /**
     * @param class-string $controllerClass
     */
    public function detect(Route $route, string $controllerClass, string $method): DetectedValidationRules|null
    {
        $rules = $this->ruleExtractor->extractFrom($route);

        if ([] === $rules) {
            return null;
        }

        $formRequest = $this->ruleExtractor->getFormRequestInstance($route);
        $formRequestClass = $formRequest ? get_class($formRequest) : null;

        return new DetectedValidationRules($rules, $formRequestClass);
    }
}
