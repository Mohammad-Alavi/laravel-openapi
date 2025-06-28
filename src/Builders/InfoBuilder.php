<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use Illuminate\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Contact;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Email;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Title;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields\Version;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\URL;

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
            ->name(
                optional(
                    Arr::get($config, 'name'),
                    static function (string|null $value): Name {
                        return Name::create($value);
                    },
                ),
            )->email(
                optional(
                    Arr::get($config, 'email'),
                    static function (string|null $value): Email {
                        return Email::create($value);
                    },
                ),
            )->url(
                optional(
                    Arr::get($config, 'url'),
                    static function (string|null $value): URL {
                        return URL::create($value);
                    },
                ),
            );
    }

    protected function buildLicense(array $config): License
    {
        return License::create(
            Name::create(Arr::get($config, 'name')),
            optional(
                Arr::get($config, 'url'),
                static function (string|null $value): URL {
                    return URL::create($value);
                },
            ),
        );
    }
}
