<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use Throwable;

final class InvalidEmailException extends DomainError
{
    public function __construct(
        string $email,
        array $details = [],
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct("Invalid E-mail '{$email}'", $details, $code, $previous);
    }
}
