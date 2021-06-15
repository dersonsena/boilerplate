<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidDomainParamException;
use App\Shared\Domain\ValueObjectBase;

final class Phone extends ValueObjectBase
{
    private string $phone;
    private string $ddd;
    private string $ddi;

    final private function __construct(string $phone, string $ddd, string $ddi = '+55')
    {
        $this->checkIfPhoneIsEmptyOrNull($phone);
        $this->checkIfDDDIsEmptyOrNull($ddd);

        $phoneSanitized = (string)preg_replace('/[^a-zA-Z0-9]/', '', $phone);
        $dddSanitized = (string)preg_replace('/[\s+()]/', '', $ddd);
        $ddiSanitized = (string)preg_replace('/[+\s+]/', '', $ddi);

        $this->checkPhoneHas8Or9Chars($phoneSanitized);

        $this->checkIfDDDHasTwoChars($dddSanitized);

        $this->checkIfDDDIsNumeric($dddSanitized);

        $this->checkIfDDIHasTwoChars($ddiSanitized);

        $this->checkIfDDIIsNumeric($ddiSanitized);

        $this->phone = $phoneSanitized;
        $this->ddd = $dddSanitized;
        $this->ddi = $ddiSanitized;
    }

    public static function createFromPieces(string $phone, string $ddd, string $ddi = '+55'): self
    {
        return new static($phone, $ddd, $ddi);
    }

    public static function createFromDddAndPhone(string $phoneWithDdd): self
    {
        $ddd = substr($phoneWithDdd, 0, 2);
        $phone = trim(substr($phoneWithDdd, 2, 9));

        return new static($phone, $ddd);
    }

    public function isMobile(): bool
    {
        if (strlen($this->phone) > 8) {
            return true;
        }

        return false;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getDdd(): string
    {
        return $this->ddd;
    }

    public function getDdi(): string
    {
        return $this->ddi;
    }

    private function checkIfPhoneIsEmptyOrNull(string $phone): void
    {
        if (empty($phone)) {
            throw new InvalidDomainParamException("Phone number cannot be empty or null.");
        }
    }

    private function checkIfDDDIsEmptyOrNull(string $ddd): void
    {
        if (empty($ddd)) {
            throw new InvalidDomainParamException("DDD cannot be empty or null.");
        }
    }

    private function checkPhoneHas8Or9Chars(string $phone): void
    {
        if (strlen($phone) < 8 || strlen($phone) > 9) {
            throw new InvalidDomainParamException("Phone '{$phone}' number must be 8 or 9 digits");
        }
    }

    private function checkIfDDDHasTwoChars(string $ddd): void
    {
        if (strlen($ddd) !== 2) {
            throw new InvalidDomainParamException('DDD must be only 2 characters.');
        }
    }

    private function checkIfDDDIsNumeric(string $ddd): void
    {
        if (!is_numeric($ddd)) {
            throw new InvalidDomainParamException('DDD must be numeric.');
        }
    }

    private function checkIfDDIHasTwoChars(string $ddi): void
    {
        if (strlen($ddi) !== 2) {
            throw new InvalidDomainParamException('DDI must be only 2 characters.');
        }
    }

    private function checkIfDDIIsNumeric(string $ddi): void
    {
        if (!is_numeric($ddi)) {
            throw new InvalidDomainParamException('DDI must be numeric.');
        }
    }

    public function value()
    {
        return $this->ddi . $this->ddd . $this->phone;
    }

    public function valueWithoutDdi(): string
    {
        return $this->ddd . $this->phone;
    }
}
