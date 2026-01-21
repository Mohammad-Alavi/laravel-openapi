<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support;

/**
 * HTTP methods supported by OpenAPI Path Item Object.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#path-item-object
 */
enum HttpMethod: string
{
    case GET = 'get';
    case PUT = 'put';
    case POST = 'post';
    case DELETE = 'delete';
    case OPTIONS = 'options';
    case HEAD = 'head';
    case PATCH = 'patch';
    case TRACE = 'trace';
    case QUERY = 'query';
}
