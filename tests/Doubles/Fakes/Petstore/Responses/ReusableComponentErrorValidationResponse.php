<?php

namespace Tests\Doubles\Fakes\Petstore\Responses;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\MediaType;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Response;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Schema;

class ReusableComponentErrorValidationResponse extends ReusableResponseFactory
{
    public function build(): Response
    {
        $schema = Schema::object('object_test')->properties(
            Schema::string('string_test')->example('The given data was invalid.'),
            Schema::object('object_test')
                ->additionalProperties(
                    Schema::array('array_test')->items(Schema::string('string_test')),
                )
                ->example(['field' => ['Something is wrong with this field!']]),
        );

        return Response::unprocessableEntity()
            ->content(
                MediaType::json()->schema($schema),
            );
    }
}
