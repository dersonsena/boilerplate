<?php

declare(strict_types=1);

namespace App\Shared\Application\Exceptions;

use Exception;
use Throwable;
use App\Shared\Application\Contracts\UseCaseException;

class UseCaseError extends Exception implements UseCaseException
{
    protected array $details = [];

    public function __construct($message = 'Use Case Exception', $details = [], $code = 0, Throwable $previous = null)
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
