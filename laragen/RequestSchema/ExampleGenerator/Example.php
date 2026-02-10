<?php

namespace MohammadAlavi\Laragen\RequestSchema\ExampleGenerator;

use Illuminate\Foundation\Http\FormRequest;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

abstract readonly class Example
{
    final public function __construct(
        protected string $attribute,
        protected LooseFluentDescriptor $schema,
        protected array $validationRules,
        protected array $nestedRuleset,
        protected LooseFluentDescriptor $baseSchema,
        protected array $allRules,
    ) {
    }

    abstract public static function rule(): string;

    abstract public function values(): array;

    public function format(): DefinedFormat|null
    {
        return null;
    }

    public function shouldBeGeneratedFor(string $request, string $attribute): bool
    {
        $except = $this->except();
        if (array_key_exists($request, $except)) {
            $exceptAttributes = $except[$request];
            if (is_array($exceptAttributes) && in_array($attribute, $exceptAttributes, true)) {
                return false;
            }
            if (is_string($exceptAttributes) && $attribute === $exceptAttributes) {
                return false;
            }
        }

        $only = $this->only();
        if (array_key_exists($request, $only)) {
            $onlyAttributes = $only[$request];
            if (is_array($onlyAttributes) && !in_array($attribute, $onlyAttributes, true)) {
                return false;
            }
            if (is_string($onlyAttributes) && $attribute !== $onlyAttributes) {
                return false;
            }
        }

        return true;
    }

    /** Return an array of request class names as keys and either a string attribute name or an array of attribute names as values.
     * The example will not be generated for these attributes in the specified requests.
     *
     * @return array<class-string<FormRequest>, string|array<int, string>>
     */
    public function except(): array
    {
        return [];
    }

    /** Return an array of request class names as keys and either a string attribute name or an array of attribute names as values.
     * The example will only be generated for these attributes in the specified requests.
     *
     * @return array<class-string<FormRequest>, string|array<int, string>>
     */
    public function only(): array
    {
        return [];
    }
}
