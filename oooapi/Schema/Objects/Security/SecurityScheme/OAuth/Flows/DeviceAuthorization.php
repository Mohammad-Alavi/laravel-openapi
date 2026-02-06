<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flow;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

/**
 * OAuth Device Authorization Flow configuration.
 *
 * The Device Authorization flow (RFC 8628) is designed for devices with
 * limited input capabilities, such as smart TVs and IoT devices. The device
 * displays a code that the user enters on a separate device with a browser.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#oauth-flow-object
 * @see https://datatracker.ietf.org/doc/html/rfc8628
 */
final readonly class DeviceAuthorization extends Flow
{
    /**
     * @param string $deviceAuthorizationUrl The device authorization URL (REQUIRED)
     * @param string $tokenUrl The token URL for the flow (REQUIRED)
     * @param string|null $refreshUrl The refresh URL for the flow
     * @param ScopeCollection|null $scopeCollection Available scopes for this flow
     */
    private function __construct(
        private string $deviceAuthorizationUrl,
        private string $tokenUrl,
        string|null $refreshUrl,
        ScopeCollection|null $scopeCollection,
    ) {
        parent::__construct($refreshUrl, $scopeCollection);
    }

    public static function create(
        string $deviceAuthorizationUrl,
        string $tokenUrl,
        string|null $refreshUrl = null,
        ScopeCollection|null $scopeCollection = null,
    ): self {
        return new self($deviceAuthorizationUrl, $tokenUrl, $refreshUrl, $scopeCollection);
    }

    public function toArray(): array
    {
        return Arr::filter([
            'deviceAuthorizationUrl' => $this->deviceAuthorizationUrl,
            'tokenUrl' => $this->tokenUrl,
            'refreshUrl' => $this->refreshUrl,
            'scopes' => $this->scopeCollection,
        ]);
    }
}
