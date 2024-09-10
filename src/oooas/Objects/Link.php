<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $operationRef
 * @property string|null $operationId
 * @property string|null $description
 * @property Server|null $server
 */
class Link extends BaseObject
{
    /**
     * @var string|null
     */
    protected $operationRef;

    /**
     * @var string|null
     */
    protected $operationId;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var Server|null
     */
    protected $server;

    /**
     * @return static
     */
    public function operationRef(string|null $operationRef): self
    {
        $instance = clone $this;

        $instance->operationRef = $operationRef;

        return $instance;
    }

    /**
     * @return static
     */
    public function operationId(string|null $operationId): self
    {
        $instance = clone $this;

        $instance->operationId = $operationId;

        return $instance;
    }

    /**
     * @return static
     */
    public function description(string|null $description): self
    {
        $instance = clone $this;

        $instance->description = $description;

        return $instance;
    }

    /**
     * @return static
     */
    public function server(Server|null $server): self
    {
        $instance = clone $this;

        $instance->server = $server;

        return $instance;
    }

    protected function generate(): array
    {
        return Arr::filter([
            'operationRef' => $this->operationRef,
            'operationId' => $this->operationId,
            'description' => $this->description,
            'server' => $this->server,
        ]);
    }
}
