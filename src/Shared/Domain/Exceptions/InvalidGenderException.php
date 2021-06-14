<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use DomainException;
use Throwable;

final class InvalidGenderException extends DomainException
{
    public function __construct(string $gender, $code = 0, Throwable $previous = null)
    {
        $message = "Invalid gender '{$gender}'";
        parent::__construct($message, $code, $previous);
    }
}
