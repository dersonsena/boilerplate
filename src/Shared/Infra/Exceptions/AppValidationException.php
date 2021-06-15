<?php

declare(strict_types=1);

namespace App\Shared\Infra\Exceptions;

class AppValidationException extends AppException
{
    protected $message = 'Application Validation Error';
    protected string $type = AppExceptionBase::TYPE_INVALID_INPUT;
}
