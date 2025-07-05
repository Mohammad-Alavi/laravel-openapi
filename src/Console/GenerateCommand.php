<?php

namespace MohammadAlavi\LaravelOpenApi\Console;

use Illuminate\Console\Command;
use MohammadAlavi\LaravelOpenApi\Generator;

class GenerateCommand extends Command
{
    protected $signature = 'openapi:generate {collection=default}';
    protected $description = 'Generate OpenAPI specification';

    public function handle(Generator $generator): void
    {
        if (!config()->has('openapi.collections.' . $this->argument('collection'))) {
            $this->error('Collection "' . $this->argument('collection') . '" does not exist.');

            return;
        }

        $this->line(
            \Safe\json_encode(
                $generator->generate($this->argument('collection')),
                JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE,
            ),
        );
    }
}
