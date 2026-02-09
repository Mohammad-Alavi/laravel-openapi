<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema;

enum RequestTarget: string
{
    case BODY = 'body';
    case QUERY = 'query';
}
