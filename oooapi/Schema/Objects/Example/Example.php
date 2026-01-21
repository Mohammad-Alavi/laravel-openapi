<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Fields\ExternalValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;
use Webmozart\Assert\Assert;

/**
 * Example Object.
 *
 * Provides an example of a schema or media type. There are two groups of
 * value-providing fields that are mutually exclusive with each other:
 *
 * Group A (legacy, mutually exclusive within group):
 *   - value: Embedded literal example
 *   - externalValue: URI pointing to external example
 *
 * Group B (OAS 3.2+, can coexist within group):
 *   - dataValue: Unserialized/logical data representation
 *   - serializedValue: Serialized string representation
 *
 * Fields from Group A cannot be used together with fields from Group B.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#example-object
 */
final class Example extends ExtensibleObject
{
    private Summary|null $summary = null;
    private Description|null $description = null;

    /** @var mixed Embedded literal example (Group A) */
    private mixed $value = null;

    /** @var ExternalValue|null URI pointing to external example (Group A) */
    private ExternalValue|null $externalValue = null;

    /** @var mixed Unserialized/logical data representation (Group B, OAS 3.2+) */
    private mixed $dataValue = null;

    /** @var string|null Serialized string representation (Group B, OAS 3.2+) */
    private string|null $serializedValue = null;

    public static function create(): self
    {
        return new self();
    }

    /**
     * Set brief description of the example.
     */
    public function summary(string $summary): self
    {
        $clone = clone $this;

        $clone->summary = Summary::create($summary);

        return $clone;
    }

    /**
     * Set long description of the example.
     *
     * CommonMark syntax MAY be used for rich text representation.
     */
    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    /**
     * Set embedded literal example value.
     *
     * The value field and externalValue field are mutually exclusive.
     * This field cannot be used together with dataValue or serializedValue.
     *
     * @param mixed $value Any value representing the example
     *
     * @see https://spec.openapis.org/oas/v3.2.0#example-object
     */
    public function value(mixed $value): self
    {
        $this->assertGroupAMutualExclusivity('value');
        $this->assertGroupsAreMutuallyExclusive('value');

        $clone = clone $this;

        $clone->value = $value;

        return $clone;
    }

    /**
     * Set URI pointing to the literal example.
     *
     * This provides the capability to reference examples that cannot easily
     * be included in JSON or YAML documents. The externalValue field is
     * mutually exclusive of the value field. This field cannot be used
     * together with dataValue or serializedValue.
     *
     * @param string $externalValue URI pointing to external example
     *
     * @see https://spec.openapis.org/oas/v3.2.0#example-object
     */
    public function externalValue(string $externalValue): self
    {
        $this->assertGroupAMutualExclusivity('externalValue');
        $this->assertGroupsAreMutuallyExclusive('externalValue');

        $clone = clone $this;

        $clone->externalValue = ExternalValue::create($externalValue);

        return $clone;
    }

    /**
     * Set the unserialized/logical data representation.
     *
     * The dataValue shows the logical structure that developers work with
     * in their code, before any serialization is applied. This field can
     * be used together with serializedValue to show both representations.
     *
     * This field cannot be used together with value or externalValue.
     *
     * @param mixed $dataValue The unserialized data structure
     *
     * @see https://spec.openapis.org/oas/v3.2.0#example-object
     */
    public function dataValue(mixed $dataValue): self
    {
        $this->assertGroupsAreMutuallyExclusive('dataValue');

        $clone = clone $this;

        $clone->dataValue = $dataValue;

        return $clone;
    }

    /**
     * Set the serialized string representation.
     *
     * The serializedValue demonstrates the actual format transmitted over HTTP,
     * with all relevant percent-encoding or other encoding/escaping applied.
     * This field can be used together with dataValue to show both representations.
     *
     * This field cannot be used together with value or externalValue.
     *
     * @param string $serializedValue The serialized representation
     *
     * @see https://spec.openapis.org/oas/v3.2.0#example-object
     */
    public function serializedValue(string $serializedValue): self
    {
        $this->assertGroupsAreMutuallyExclusive('serializedValue');

        $clone = clone $this;

        $clone->serializedValue = $serializedValue;

        return $clone;
    }

    /**
     * Assert mutual exclusivity within Group A (value and externalValue).
     */
    private function assertGroupAMutualExclusivity(string $fieldBeingSet): void
    {
        if ('value' === $fieldBeingSet) {
            Assert::null(
                $this->externalValue,
                'value and externalValue fields are mutually exclusive.',
            );
        }

        if ('externalValue' === $fieldBeingSet) {
            Assert::null(
                $this->value,
                'value and externalValue fields are mutually exclusive.',
            );
        }
    }

    /**
     * Assert that Group A (value/externalValue) and Group B (dataValue/serializedValue) are mutually exclusive.
     */
    private function assertGroupsAreMutuallyExclusive(string $fieldBeingSet): void
    {
        $isGroupAField = in_array($fieldBeingSet, ['value', 'externalValue'], true);
        $isGroupBField = in_array($fieldBeingSet, ['dataValue', 'serializedValue'], true);

        if ($isGroupAField) {
            Assert::null(
                $this->dataValue,
                'value/externalValue cannot be used together with dataValue/serializedValue. '
                . 'See: https://spec.openapis.org/oas/v3.2.0#example-object',
            );
            Assert::null(
                $this->serializedValue,
                'value/externalValue cannot be used together with dataValue/serializedValue. '
                . 'See: https://spec.openapis.org/oas/v3.2.0#example-object',
            );
        }

        if ($isGroupBField) {
            Assert::null(
                $this->value,
                'dataValue/serializedValue cannot be used together with value/externalValue. '
                . 'See: https://spec.openapis.org/oas/v3.2.0#example-object',
            );
            Assert::null(
                $this->externalValue,
                'dataValue/serializedValue cannot be used together with value/externalValue. '
                . 'See: https://spec.openapis.org/oas/v3.2.0#example-object',
            );
        }
    }

    public function toArray(): array
    {
        return Arr::filter([
            'summary' => $this->summary,
            'description' => $this->description,
            'value' => $this->value,
            'externalValue' => $this->externalValue,
            'dataValue' => $this->dataValue,
            'serializedValue' => $this->serializedValue,
        ]);
    }
}
