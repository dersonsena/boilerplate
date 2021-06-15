<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidPostalCodeException;
use App\Shared\Domain\ValueObjectBase;

final class PostalCode extends ValueObjectBase
{
    private string $postalCode;

    /**
     * @throws InvalidPostalCodeException
     */
    public function __construct(string $postalCode)
    {
        if (empty($postalCode)) {
            throw new InvalidPostalCodeException('Postal code cannot be empty');
        }

        $postalCodeSanitized = (string)preg_replace('/[^a-zA-Z0-9]/', '', $postalCode);

        if (!ctype_digit($postalCodeSanitized)) {
            throw new InvalidPostalCodeException('The postal code must contain numbers only');
        }

        if (strlen($postalCodeSanitized) !== 8) {
            throw new InvalidPostalCodeException('Postal code must be exactly 8 characters long');
        }

        $this->postalCode = $postalCodeSanitized;
    }

    /**
     * @inheritDoc
     */
    public function value()
    {
        return $this->postalCode;
    }

    public function formatted(): string
    {
        return preg_replace("/(\d{5})(\d{3})/", "\$1-\$2", $this->postalCode);
    }
}
