<?php

namespace MohammadAlavi\Laragen\RequestSchema;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use MohammadAlavi\LaravelRulesToSchema\RuleToSchema as BaseRuleToSchema;
use MohammadAlavi\LaravelRulesToSchema\ValidationRuleNormalizer;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use Webmozart\Assert\Assert;

final class RuleToSchema
{
    public static function transform(array|string|Route $rule): LooseFluentDescriptor
    {
        $request = null;
        if (is_string($rule)) {
            Assert::isAOf(
                $rule,
                FormRequest::class,
                "Class {$rule} does not implement " . FormRequest::class . ' and can not be parsed.',
            );
            $request = $rule;
            $instance = new $rule();

            $rule = method_exists($instance, 'rules') ? app()->call([$instance, 'rules']) : [];
        }

        if ($rule instanceof Route) {
            $route = $rule;
            $extractor = app(RuleExtractor::class);

            $request = $extractor->getFormRequestInstance($route);
            $request = $request ? get_class($request) : null;

            $rule = $extractor->extractFrom($route);
        }

        return app(BaseRuleToSchema::class)->transform(
            (new ValidationRuleNormalizer($rule))->getRules(),
            $request,
        );
    }
}
