<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract;

use Illuminate\Support\Facades\File;

trait Generator
{
    /**
     * Saves the object as a JSON file.
     *
     * @param string|null $path path without trailing slash. If null, the file will be saved in the root directory.
     * @param string $name the name of the file
     */
    public function toJsonFile(
        string $name,
        string|null $path = null,
        int $options = JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
    ): bool|int {
        if (!is_null($path) && '' !== $path && '0' !== $path) {
            return File::put(
                $path . sprintf('/%s.json', $name),
                $this->toJson($options),
            );
        }

        return File::put(
            $name . '.json',
            $this->toJson($options),
        );
    }

    public function toJson(
        int $options = JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
    ): string {
        return \Safe\json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * It should not be used directly.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    abstract public function toArray(): array;

    /**
     * Returns the object as an array.
     *
     * @throws \JsonException
     */
    public function unserializeToArray(): array
    {
        return json_decode($this->toJson(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function toObjectIfEmpty(
        Generatable|ReadonlyGeneratable|array|null $value,
    ): Generatable|ReadonlyGeneratable|\stdClass {
        $orgValue = $value;
        if ($value instanceof Generatable || $value instanceof ReadonlyGeneratable) {
            $value = $value->toArray();
        }

        if (blank($value)) {
            return new \stdClass();
        }

        return $orgValue;
    }

    public function toNullIfEmpty(): static|null
    {
        return when(blank($this->toArray()), null, $this);
    }
}
