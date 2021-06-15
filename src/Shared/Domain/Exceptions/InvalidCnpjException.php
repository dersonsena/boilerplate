<?php

namespace App\Shared\Domain\Exceptions;

use Throwable;

final class InvalidCnpjException extends DomainError
{
    public function __construct(
        string $message = "Invalid CNPJ",
        array $details = [],
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $details, $code, $previous);
    }
}
