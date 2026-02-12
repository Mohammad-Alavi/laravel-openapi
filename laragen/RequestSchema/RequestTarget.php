<?php

namespace MohammadAlavi\Laragen\RequestSchema;

enum RequestTarget: string
{
    case BODY = 'body';
    case QUERY = 'query';
}
