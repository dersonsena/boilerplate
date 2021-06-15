<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use Throwable;

final class InvalidGenderException extends DomainError
{
    public function __construct(string $gender, array $details = [], int $code = 0, Throwable $previous = null)
    {
        $details = array_merge($details, ['gender' => $gender]);
        parent::__construct("Invalid gender '{$gender}'", $details, $code, $previous);
    }
}
