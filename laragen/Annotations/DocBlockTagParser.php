<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\Annotations;

use function Safe\preg_match_all;
use function Safe\preg_split;

final class DocBlockTagParser
{
    /**
     * @param class-string $controllerClass
     *
     * @return DetectedResponseAnnotation[]
     */
    public static function extractResponseTags(string $controllerClass, string $method): array
    {
        $docComment = self::getDocComment($controllerClass, $method);

        if (null === $docComment) {
            return [];
        }

        $tags = [];

        // Match: @response {status?} {json}
        // Status is optional (defaults to 200). JSON starts with { and goes to end of line.
        $matches = [];
        $count = preg_match_all('/@response\s+(?:(\d{3})\s+)?(\{.+\})\s*$/m', $docComment, $matches, PREG_SET_ORDER);

        if ($count > 0) {
            foreach ($matches as $match) {
                $status = '' !== $match[1] ? (int) $match[1] : 200;
                $tags[] = new DetectedResponseAnnotation($status, $match[2]);
            }
        }

        return $tags;
    }

    /**
     * @param class-string $controllerClass
     *
     * @return DetectedBodyParam[]
     */
    public static function extractBodyParamTags(string $controllerClass, string $method): array
    {
        $docComment = self::getDocComment($controllerClass, $method);

        if (null === $docComment) {
            return [];
        }

        $tags = [];

        // Match: @bodyParam {name} {type} {required?} {description?}
        $matches = [];
        $count = preg_match_all('/@bodyParam\s+(\S+)\s+(\S+)(.*)$/m', $docComment, $matches, PREG_SET_ORDER);

        if ($count > 0) {
            foreach ($matches as $match) {
                $name = $match[1];
                $type = $match[2];
                $rest = trim($match[3]);

                $required = false;
                if (str_starts_with($rest, 'required')) {
                    $required = true;
                    $rest = trim(substr($rest, 8));
                }

                $description = '' !== $rest ? $rest : null;

                $tags[] = new DetectedBodyParam($name, $type, $required, $description);
            }
        }

        return $tags;
    }

    /**
     * @param class-string $controllerClass
     *
     * @return DetectedQueryParam[]
     */
    public static function extractQueryParamTags(string $controllerClass, string $method): array
    {
        $docComment = self::getDocComment($controllerClass, $method);

        if (null === $docComment) {
            return [];
        }

        $tags = [];

        // Match: @queryParam {name} {type?} {description?}
        $matches = [];
        $count = preg_match_all('/@queryParam\s+(\S+)(.*)$/m', $docComment, $matches, PREG_SET_ORDER);

        if ($count > 0) {
            foreach ($matches as $match) {
                $name = $match[1];
                $rest = trim($match[2]);

                $type = 'string';
                $description = null;

                if ('' !== $rest) {
                    /** @var list<string> $parts */
                    $parts = preg_split('/\s+/', $rest, 2);
                    $type = $parts[0];
                    $description = isset($parts[1]) && '' !== $parts[1] ? $parts[1] : null;
                }

                $tags[] = new DetectedQueryParam($name, $type, $description);
            }
        }

        return $tags;
    }

    /**
     * @param class-string $controllerClass
     */
    private static function getDocComment(string $controllerClass, string $method): string|null
    {
        if (!method_exists($controllerClass, $method)) {
            return null;
        }

        $reflection = new \ReflectionMethod($controllerClass, $method);
        $docComment = $reflection->getDocComment();

        return false !== $docComment ? $docComment : null;
    }
}
