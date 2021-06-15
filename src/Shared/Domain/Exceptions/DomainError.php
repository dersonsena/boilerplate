<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use App\Shared\Domain\Contracts\DomainException;
use Exception;
use Throwable;

class DomainError extends Exception implements DomainException
{
    protected array $details = [];

    public function __construct($message = 'Domain Exception', $details = [], $code = 0, Throwable $previous = null)
    {
        $this->message = $message;
        $this->details = $details;
        parent::__construct($this->message, $code, $previous);
    }

    public function details(): array
    {
        return $this->details;
    }
}
