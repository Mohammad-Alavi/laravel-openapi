<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use Illuminate\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Email;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;

class InfoBuilder
{
    public function build(array $config): Info
    {
        $info = Info::create(
            Title::create(Arr::get($config, 'title')),
            Version::create(Arr::get($config, 'version')),
        )->description(
            Arr::get($config, 'description')
                ? Description::create(Arr::get($config, 'description'))
                : null,
        );

        if (
            Arr::has($config, 'contact')
            && (
                array_key_exists('name', $config['contact'])
                || array_key_exists('email', $config['contact'])
                || array_key_exists('url', $config['contact'])
            )
        ) {
            $info = $info->contact($this->buildContact($config['contact']));
        }

        if (Arr::has($config, 'license') && array_key_exists('name', $config['license'])) {
            $info = $info->license($this->buildLicense($config['license']));
        }

        $extensions = $config['extensions'] ?? [];

        foreach ($extensions as $key => $value) {
            $info->addExtension(Extension::create($key, $value));
        }

        return $info;
    }

    protected function buildContact(array $config): Contact
    {
        return Contact::create()
            ->name(optional(Arr::get($config, 'name'), static fn (string|null $value) => Name::create($value)))
            ->email(optional(Arr::get($config, 'email'), static fn (string|null $value) => Email::create($value)))
            ->url(optional(Arr::get($config, 'url'), static fn (string|null $value) => URL::create($value)));
    }

    protected function buildLicense(array $config): License
    {
        return License::create()
            ->name(Arr::get($config, 'name'))
            ->url(Arr::get($config, 'url'));
    }
}
