<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema;

trait ForwardsCalls
{
    /**
     * Forward a method call to the given object, returning $this if the forwarded call returned itself.
     *
     * @throws \BadMethodCallException
     */
    protected function forwardDecoratedCallTo(mixed $object, string $method, array $parameters): mixed
    {
        $result = $this->forwardCallTo($object, $method, $parameters);

        if ($result === $object) {
            return $this;
        }

        return $result;
    }

    /**
     * Forward a method call to the given object.
     *
     * @throws \BadMethodCallException
     */
    protected function forwardCallTo(mixed $object, string $method, array $parameters): mixed
    {
        try {
            return $object->{$method}(...$parameters);
        } catch (\Error|\BadMethodCallException $e) {
            $pattern = '~^Call to undefined method (?P<class>[^:]+)::(?P<method>[^\(]+)\(\)$~';

            if (
                !preg_match($pattern, $e->getMessage(), $matches)
                || $matches['method'] !== $method
                || $matches['class'] !== get_class($object)
            ) {
                throw $e;
            }

            static::throwBadMethodCallException($method);
        }
    }

    /**
     * Throw a bad method call exception for the given method.
     *
     * @throws \BadMethodCallException
     */
    protected static function throwBadMethodCallException(string $method): never
    {
        throw new \BadMethodCallException(sprintf('Call to undefined method %s::%s()', static::class, $method));
    }
}
