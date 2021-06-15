<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidDomainParamException;
use App\Shared\Domain\Exceptions\InvalidEmailException;
use App\Shared\Domain\ValueObjectBase;

final class Email extends ValueObjectBase
{
    private string $email;

    public function __construct(string $email)
    {
        if (empty($email)) {
            throw new InvalidDomainParamException('E-mail is empty.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException($email);
        }

        $this->email = $email;
    }

    public function value()
    {
        return $this->email;
    }
}
