<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support;

/**
 * Parameter location values for OpenAPI parameters and API key security schemes.
 *
 * Defines where a parameter or API key is expected to be found in a request.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#parameter-object
 * @see https://spec.openapis.org/oas/v3.1.0#security-scheme-object
 */
enum ParameterLocation: string
{
    case QUERY = 'query';
    case HEADER = 'header';
    case PATH = 'path';
    case COOKIE = 'cookie';
}
