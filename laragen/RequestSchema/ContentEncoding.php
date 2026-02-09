<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema;

enum ContentEncoding: string
{
    case JSON = 'application/json';
    case MULTIPART_FORM_DATA = 'multipart/form-data';
}
