<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Annotation;

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\Annotations\DocBlockTagParser;
use MohammadAlavi\Laragen\RequestSchema\RequestDetector;

final readonly class AnnotationQueryParamDetector implements RequestDetector
{
    /**
     * @param class-string $controllerClass
     *
     * @return \MohammadAlavi\Laragen\Annotations\DetectedQueryParam[]|null
     */
    public function detect(Route $route, string $controllerClass, string $method): mixed
    {
        $tags = DocBlockTagParser::extractQueryParamTags($controllerClass, $method);

        return [] !== $tags ? $tags : null;
    }
}
