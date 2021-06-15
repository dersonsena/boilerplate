<?php

namespace App\Shared\Domain\Exceptions;

use Throwable;

final class InvalidCpfException extends DomainError
{
    public function __construct(
        string $message = '',
        array $details = [],
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct("Invalid CPF", $details, $code, $previous);
    }
}
