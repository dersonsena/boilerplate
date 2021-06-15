<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use Throwable;

final class InvalidDomainParamException extends DomainError
{
    public function __construct(
        string $message,
        array $details = [],
        int $code = 0,
        Throwable $previous = null
    ) {
        $details = array_merge([
            'class' => get_called_class(),
            'file' => $this->getFile() . ':' . $this->getLine()
        ], $details);

        parent::__construct("Invalid domain param: {$message}", $details, $code, $previous);
    }
}
