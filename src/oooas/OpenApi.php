<?php

namespace MohammadAlavi\ObjectOrientedOAS;

use MohammadAlavi\ObjectOrientedOAS\Exceptions\ValidationException;
use MohammadAlavi\ObjectOrientedOAS\Objects\BaseObject;
use MohammadAlavi\ObjectOrientedOAS\Objects\Components;
use MohammadAlavi\ObjectOrientedOAS\Objects\ExternalDocs;
use MohammadAlavi\ObjectOrientedOAS\Objects\Info;
use MohammadAlavi\ObjectOrientedOAS\Objects\PathItem;
use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityRequirement;
use MohammadAlavi\ObjectOrientedOAS\Objects\Server;
use MohammadAlavi\ObjectOrientedOAS\Objects\Tag;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;
use JsonSchema\Constraints\BaseConstraint;
use JsonSchema\Validator;

/**
 * @property string|null $openapi
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Info|null $info
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Server[]|null $servers
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\PathItem[]|null $paths
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Components|null $components
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\SecurityRequirement[]|null $security
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Tag[]|null $tags
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\ExternalDocs|null $externalDocs
 */
class OpenApi extends BaseObject
{
    const OPENAPI_3_0_0 = '3.0.0';
    const OPENAPI_3_0_1 = '3.0.1';
    const OPENAPI_3_0_2 = '3.0.2';

    /**
     * @var string|null
     */
    protected $openapi;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Info|null
     */
    protected $info;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Server[]|null
     */
    protected $servers;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\PathItem[]|null
     */
    protected $paths;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Components|null
     */
    protected $components;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\SecurityRequirement[]|null
     */
    protected $security;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Tag[]|null
     */
    protected $tags;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\ExternalDocs|null
     */
    protected $externalDocs;

    /**
     * @param string|null $openapi
     * @return static
     */
    public function openapi(?string $openapi): self
    {
        $instance = clone $this;

        $instance->openapi = $openapi;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Info|null $info
     * @return static
     */
    public function info(?Info $info): self
    {
        $instance = clone $this;

        $instance->info = $info;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Server[] $servers
     * @return static
     */
    public function servers(Server ...$servers): self
    {
        $instance = clone $this;

        $instance->servers = $servers ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\PathItem[] $paths
     * @return static
     */
    public function paths(PathItem ...$paths): self
    {
        $instance = clone $this;

        $instance->paths = $paths ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Components|null $components
     * @return static
     */
    public function components(?Components $components): self
    {
        $instance = clone $this;

        $instance->components = $components;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\SecurityRequirement[] $security
     * @return static
     */
    public function security(SecurityRequirement ...$security): self
    {
        $instance = clone $this;

        $instance->security = $security ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Tag[] $tags
     * @return static
     */
    public function tags(Tag ...$tags): self
    {
        $instance = clone $this;

        $instance->tags = $tags ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\ExternalDocs|null $externalDocs
     * @return static
     */
    public function externalDocs(?ExternalDocs $externalDocs): self
    {
        $instance = clone $this;

        $instance->externalDocs = $externalDocs;

        return $instance;
    }

    /**
     * @throws \MohammadAlavi\ObjectOrientedOAS\Exceptions\ValidationException
     */
    public function validate(): void
    {
        if (!class_exists('JsonSchema\Validator')) {
            throw new \RuntimeException('justinrainbow/json-schema should be installed for validation');
        }

        $data = BaseConstraint::arrayToObjectRecursive($this->generate());

        $schema = file_get_contents(
            realpath(__DIR__ . '/schemas/v3.0.json')
        );
        $schema = json_decode($schema);

        $validator = new Validator();
        $validator->validate($data, $schema);

        if (!$validator->isValid()) {
            throw new ValidationException($validator->getErrors());
        }
    }

    /**
     * @return array
     */
    protected function generate(): array
    {
        $paths = [];
        foreach ($this->paths ?? [] as $path) {
            $paths[$path->route] = $path;
        }

        return Arr::filter([
            'openapi' => $this->openapi,
            'info' => $this->info,
            'servers' => $this->servers,
            'paths' => $paths ?: null,
            'components' => $this->components,
            'security' => $this->security,
            'tags' => $this->tags,
            'externalDocs' => $this->externalDocs,
        ]);
    }
}
