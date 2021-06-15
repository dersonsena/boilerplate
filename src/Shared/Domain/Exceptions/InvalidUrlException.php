<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use Throwable;

final class InvalidUrlException extends DomainError
{
    public function __construct(string $url, $details = [], $code = 0, Throwable $previous = null)
    {
        parent::__construct("Invalid URL: {$url}" . $url, $details, $code, $previous);
    }
}
