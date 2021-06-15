<?php

namespace App\Shared\Domain\Exceptions;

use Throwable;

final class InvalidCpfException extends DomainError
{
    public function __construct(
        string $message = '',
        array $details = [],
        int $code = 0,
        Throwable $previous = null
    ) {
        $message = !empty($message) ? $message : "Invalid CPF";
        parent::__construct($message, $details, $code, $previous);
    }
}
