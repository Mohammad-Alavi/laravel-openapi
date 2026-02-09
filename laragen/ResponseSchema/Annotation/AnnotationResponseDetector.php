<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema\Annotation;

use MohammadAlavi\Laragen\Annotations\DocBlockTagParser;
use MohammadAlavi\Laragen\ResponseSchema\ResponseDetector;

final readonly class AnnotationResponseDetector implements ResponseDetector
{
    /**
     * @param class-string $controllerClass
     *
     * @return \MohammadAlavi\Laragen\Annotations\DetectedResponseAnnotation[]|null
     */
    public function detect(string $controllerClass, string $method): mixed
    {
        $tags = DocBlockTagParser::extractResponseTags($controllerClass, $method);

        return [] !== $tags ? $tags : null;
    }
}
