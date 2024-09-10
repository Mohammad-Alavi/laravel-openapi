<?php

namespace MohammadAlavi\ObjectOrientedOAS\Exceptions;

class ValidationException extends \Exception
{
    protected $errors;

    public function __construct(array $errors, $message = '', $code = 0, \Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
