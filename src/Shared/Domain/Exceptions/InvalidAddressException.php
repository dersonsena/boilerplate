<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use DomainException;
use Throwable;

final class InvalidAddressException extends DomainException
{
    public function __construct($message = "Invalid Address", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
