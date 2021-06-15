<?php

declare(strict_types=1);

namespace App\Shared\Application\Exceptions;

use Throwable;

final class InvalidUseCaseParamException extends UseCaseError
{
    public function __construct(
        string $message,
        array $details = [],
        $code = 0,
        Throwable $previous = null
    ) {
        $details = array_merge([
            'class' => get_called_class(),
            'file' => $this->getFile() . ':' . $this->getLine()
        ], $details);

        parent::__construct("Invalid use case param: {$message}", $details, $code, $previous);
    }
}
