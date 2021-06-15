<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use Throwable;

final class InvalidPhoneException extends DomainError
{
    public function __construct(
        string $message = '',
        array $details = [],
        int $code = 0,
        Throwable $previous = null
    ) {
        $message = !empty($message) ? $message : "Invalid Phone";
        parent::__construct($message, $details, $code, $previous);
    }
}
