<?php

namespace MohammadAlavi\LaravelOpenApi\oooas\Schema;

abstract class BaseObject implements \JsonSerializable
{
    public string|null $ref = null;

    protected function __construct(
        public readonly string|null $objectId = null,
    ) {
    }

    final public static function create(string|null $objectId = null): static
    {
        return new static($objectId);
    }

    // TODO: It seems not all objects need the ref method.
    //  Only objects that can be Components and reusable needs this method.
    //  Maybe this can be moved to a trait + interface.
    //  https://swagger.io/specification/#components-object
    final public static function ref(string $ref, string|null $objectId = null): static
    {
        $static = new static($objectId);

        $static->ref = $ref;

        return $static;
    }

    public function toJson(
        $options = JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT,
    ): string {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize(): array
    {
        if ($this->hasReference()) {
            return ['$ref' => $this->ref];
        }

        return $this->toArray();
    }

    private function hasReference(): bool
    {
        return !is_null($this->ref);
    }

    abstract protected function toArray(): array;
}
