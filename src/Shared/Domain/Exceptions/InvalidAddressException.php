<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use Throwable;

final class InvalidAddressException extends DomainError
{
    public function __construct(
        string $message = "Invalid Address",
        array $details = [],
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $details, $code, $previous);
    }
}
