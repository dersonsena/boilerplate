<?php

namespace App\Shared\Domain\Exceptions;

use DomainException;
use Throwable;

final class InvalidCnpjException extends DomainException
{
    public function __construct($message = "Invalid CNPJ", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
