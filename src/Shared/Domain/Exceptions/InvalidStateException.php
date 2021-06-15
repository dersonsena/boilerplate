<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use Throwable;

final class InvalidStateException extends DomainError
{
    public function __construct(
        string $uf,
        array $details = [],
        int $code = 0,
        Throwable $previous = null
    ) {
        $details = array_merge($details, ['uf' => $uf]);
        parent::__construct("Invalid state '{$uf}'", $details, $code, $previous);
    }
}
