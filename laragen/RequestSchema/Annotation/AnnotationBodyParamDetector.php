<?php

namespace MohammadAlavi\Laragen\RequestSchema\Annotation;

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\Annotations\DocBlockTagParser;
use MohammadAlavi\Laragen\RequestSchema\RequestDetector;

final readonly class AnnotationBodyParamDetector implements RequestDetector
{
    /**
     * @param class-string $controllerClass
     *
     * @return \MohammadAlavi\Laragen\Annotations\DetectedBodyParam[]|null
     */
    public function detect(Route $route, string $controllerClass, string $method): mixed
    {
        $tags = DocBlockTagParser::extractBodyParamTags($controllerClass, $method);

        return [] !== $tags ? $tags : null;
    }
}
