<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\ValueObjectBase;
use InvalidArgumentException;

final class Email extends ValueObjectBase
{
    private string $email;

    public function __construct(string $email)
    {
        if (empty($email)) {
            throw new InvalidArgumentException('E-mail is empty.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid E-mail "' . $email . "'");
        }

        $this->email = $email;
    }

    public function value()
    {
        return $this->email;
    }
}
