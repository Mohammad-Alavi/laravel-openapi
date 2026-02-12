<?php

namespace MohammadAlavi\Laragen\ModelSchema;

use Illuminate\Database\Eloquent\Model;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Webmozart\Assert\Assert;

final class ModelSchemaInferrer
{
    /**
     * Infer a JSON Schema from an Eloquent model class.
     *
     * @param class-string<Model> $modelClass
     */
    public function infer(string $modelClass): JSONSchema
    {
        Assert::isAOf($modelClass, Model::class);

        $model = new $modelClass();
        $properties = [];

        // Add primary key
        $keyName = $model->getKeyName();
        if ('' !== $keyName) {
            $properties[$keyName] = $this->keySchema($model);
        }

        // Add cast-defined fields
        /** @var array<string, string> $casts */
        $casts = $model->getCasts();

        foreach ($casts as $field => $cast) {
            if ($this->isHidden($model, $field)) {
                continue;
            }

            $properties[$field] = CastAnalyzer::resolve($cast);
        }

        // Add appended fields
        foreach ($this->getAppends($model) as $appended) {
            if (!isset($properties[$appended]) && !$this->isHidden($model, $appended)) {
                $properties[$appended] = Schema::string();
            }
        }

        return Schema::object()->properties(
            ...array_map(
                static fn (string $name, JSONSchema $schema): Property => Property::create($name, $schema),
                array_keys($properties),
                array_values($properties),
            ),
        );
    }

    private function keySchema(Model $model): JSONSchema
    {
        return match ($model->getKeyType()) {
            'int', 'integer' => Schema::integer(),
            default => Schema::string(),
        };
    }

    private function isHidden(Model $model, string $field): bool
    {
        return in_array($field, $model->getHidden(), true);
    }

    /**
     * @return string[]
     */
    private function getAppends(Model $model): array
    {
        // Laravel's $appends is protected, use reflection
        $reflection = new \ReflectionProperty($model, 'appends');

        /** @var string[] $appends */
        $appends = $reflection->getValue($model);

        return $appends;
    }
}
