<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidAddressException;
use App\Shared\Domain\ValueObjectBase;

final class Address extends ValueObjectBase
{
    private string $street;
    private string $number;
    private string $district;
    private string $city;
    private string $state;
    private string $postalCode;
    private string $complement;
    private bool $sameMailingAddress;

    public function __construct(
        string $street,
        string $number,
        string $district,
        string $city,
        string $state,
        string $postalCode,
        string $complement = '',
        bool $sameMailingAddress = true
    ) {
        if (strlen($state) !== 2) {
            throw new InvalidAddressException('State must be exactly 2 characters long.');
        }

        if (strlen($postalCode) !== 8) {
            throw new InvalidAddressException(
                "Zip code must be exactly 8 characters long, '" . strlen($postalCode) . "' given."
            );
        }

        $this->street = $street;
        $this->number = $number;
        $this->district = $district;
        $this->city = $city;
        $this->state = $state;
        $this->postalCode = $postalCode;
        $this->complement = $complement;
        $this->sameMailingAddress = $sameMailingAddress;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getDistrict(): string
    {
        return $this->district;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @return string
     */
    public function getComplement(): string
    {
        return $this->complement;
    }

    /**
     * @return bool
     */
    public function isSameMailingAddress(): bool
    {
        return $this->sameMailingAddress;
    }

    public function value()
    {
        return trim("
            {$this->street}, nº {$this->number} {$this->complement} -
            {$this->district}. {$this->city} - {$this->state}. CEP {$this->postalCode}
        ");
    }
}
