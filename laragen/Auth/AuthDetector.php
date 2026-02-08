<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\Auth;

use Illuminate\Routing\Route;

final readonly class AuthDetector
{
    public function detect(Route $route): AuthScheme|null
    {
        /** @var string[] $middleware */
        $middleware = $route->middleware();

        foreach ($middleware as $item) {
            $scheme = $this->parseMiddleware($item);

            if (null !== $scheme) {
                return $scheme;
            }
        }

        return null;
    }

    private function parseMiddleware(string $middleware): AuthScheme|null
    {
        if ('auth.basic' === $middleware) {
            return AuthScheme::basic();
        }

        if ('auth' === $middleware) {
            return AuthScheme::bearer('default');
        }

        if (str_starts_with($middleware, 'auth:')) {
            $guard = substr($middleware, 5);

            return AuthScheme::bearer($guard);
        }

        return null;
    }
}
