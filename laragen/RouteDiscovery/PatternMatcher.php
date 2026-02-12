<?php

namespace MohammadAlavi\Laragen\RouteDiscovery;

use function Safe\preg_match;

final readonly class PatternMatcher
{
    /**
     * @param string[] $includePatterns
     * @param string[] $excludePatterns
     */
    public function __construct(
        private array $includePatterns,
        private array $excludePatterns,
    ) {
    }

    public function matches(string $uri): bool
    {
        $uri = ltrim($uri, '/');

        if (!$this->matchesAny($uri, $this->includePatterns)) {
            return false;
        }

        return !$this->matchesAny($uri, $this->excludePatterns);
    }

    /**
     * @param string[] $patterns
     */
    private function matchesAny(string $uri, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if ($this->matchesPattern($uri, ltrim($pattern, '/'))) {
                return true;
            }
        }

        return false;
    }

    private function matchesPattern(string $uri, string $pattern): bool
    {
        $regex = str_replace('\*', '.*', preg_quote($pattern, '#'));

        return 1 === preg_match('#^' . $regex . '$#', $uri);
    }
}
