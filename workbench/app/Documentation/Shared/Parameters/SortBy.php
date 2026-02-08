<?php

namespace Workbench\App\Documentation\Shared\Parameters;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\QueryParameter;
use Workbench\App\Documentation\WorkbenchCollection;

#[Collection(WorkbenchCollection::class)]
final class SortBy extends ParameterFactory implements ShouldBeReferenced
{
    public function component(): Parameter
    {
        return Parameter::query(
            'sortBy',
            QueryParameter::create(
                Schema::string()
                    ->enum(['name', 'created_at', 'updated_at'])
                    ->default('name')
                    ->description(
                        'The field by which to sort the results. Options are "name", "created_at", and "updated_at".',
                    ),
            ),
        );
    }
}
