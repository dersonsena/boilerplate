<?php

declare(strict_types=1);

namespace App\Shared\Infra\Exceptions;

class RuntimeException extends AppException
{
    protected $message = 'There was unexpected error';
    protected string $type = AppExceptionBase::TYPE_INTERNAL_ERROR;
}
