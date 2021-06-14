<?php

namespace App\Shared\Domain\Exceptions;

use DomainException;
use Throwable;

final class InvalidCpfException extends DomainException
{
    public function __construct($message = "Invalid CPF", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
