<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use Throwable;

final class InvalidUrlException extends DomainError
{
    public function __construct(string $url, array $details = [], int $code = 0, Throwable $previous = null)
    {
        $details = array_merge($details, ['url' => $url]);
        parent::__construct("Invalid URL '{$url}'", $details, $code, $previous);
    }
}
