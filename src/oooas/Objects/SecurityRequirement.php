<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $securityScheme
 * @property string[]|null $scopes
 */
class SecurityRequirement extends BaseObject
{
    protected string|null $securityScheme = null;

    /**
     * @var string[]|null
     */
    protected array|null $scopes = null;

    /**
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public function securityScheme(SecurityScheme|string|null $securityScheme): self
    {
        // If a SecurityScheme instance passed in, then use its Object ID.
        if ($securityScheme instanceof SecurityScheme) {
            $securityScheme = $securityScheme->objectId;
        }

        // If the $securityScheme is not a string or null, then thrown an exception.
        if (!is_string($securityScheme) && !is_null($securityScheme)) {
            throw new InvalidArgumentException(sprintf('The security scheme must either be an instance of [%s], a string or null.', SecurityScheme::class));
        }

        $instance = clone $this;

        $instance->securityScheme = $securityScheme;

        return $instance;
    }

    /**
     * @param string[] $scopes
     *
     * @return static
     */
    public function scopes(string ...$scopes): self
    {
        $instance = clone $this;

        $instance->scopes = [] !== $scopes ? $scopes : null;

        return $instance;
    }

    protected function generate(): array
    {
        return Arr::filter([
            $this->securityScheme => $this->scopes ?? [],
        ]);
    }
}
