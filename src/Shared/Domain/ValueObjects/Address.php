<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\ValueObjectBase;

final class Address extends ValueObjectBase
{
    private string $street;
    private string $number;
    private string $district;
    private string $city;
    private State $state;
    private PostalCode $postalCode;
    private string $complement;
    private bool $sameMailingAddress;

    public function __construct(
        string $street,
        string $number,
        string $district,
        string $city,
        State $state,
        PostalCode $postalCode,
        string $complement = '',
        bool $sameMailingAddress = true
    ) {
        $this->street = $street;
        $this->number = $number;
        $this->district = $district;
        $this->city = $city;
        $this->state = $state;
        $this->postalCode = $postalCode;
        $this->complement = $complement;
        $this->sameMailingAddress = $sameMailingAddress;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getDistrict(): string
    {
        return $this->district;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function getPostalCode(): PostalCode
    {
        return $this->postalCode;
    }

    public function getComplement(): string
    {
        return $this->complement;
    }

    public function isSameMailingAddress(): bool
    {
        return $this->sameMailingAddress;
    }

    public function value()
    {
        $first = "{$this->street}, nº {$this->number} {$this->complement}";
        $second = "{$this->district}. {$this->city} - {$this->state}";
        $third = "CEP {$this->postalCode}";

        return trim("{$first} - {$second}. {$third}");
    }
}
