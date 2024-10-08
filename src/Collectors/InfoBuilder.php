<?php

namespace MohammadAlavi\LaravelOpenApi\Collectors;

use Illuminate\Support\Arr;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Contact;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Info;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\License;

class InfoBuilder
{
    public function build(array $config): Info
    {
        $info = Info::create()
            ->title(Arr::get($config, 'title'))
            ->description(Arr::get($config, 'description'))
            ->version(Arr::get($config, 'version'));

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
            $info->addExtension($key, $value);
        }

        return $info;
    }

    protected function buildContact(array $config): Contact
    {
        return Contact::create()
            ->name(Arr::get($config, 'name'))
            ->email(Arr::get($config, 'email'))
            ->url(Arr::get($config, 'url'));
    }

    protected function buildLicense(array $config): License
    {
        return License::create()
            ->name(Arr::get($config, 'name'))
            ->url(Arr::get($config, 'url'));
    }
}
