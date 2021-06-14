<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\ValueObjectBase;
use InvalidArgumentException;

final class Phone extends ValueObjectBase
{
    private string $phone;
    private string $ddd;
    private string $ddi;

    private function __construct(string $phone, string $ddd, string $ddi = '+55')
    {
        $this->checkIfPhoneIsEmptyOrNull($phone);
        $this->checkIfDDDIsEmptyOrNull($ddd);

        $phoneSanitized = preg_replace('/[^a-zA-Z0-9]/', '', $phone);
        $dddSanitized = preg_replace('/[\s+()]/', '', $ddd);
        $ddiSanitized = preg_replace('/[+\s+]/', '', $ddi);

        $this->checkPhoneHas8Or9Chars($phoneSanitized);

        $this->checkIfDDDHasTwoChars($dddSanitized);

        $this->checkIfDDDIsNumeric($dddSanitized);

        $this->checkIfDDIHasTwoChars($ddiSanitized);

        $this->checkIfDDIIsNumeric($ddiSanitized);

        $this->phone = (string)$phoneSanitized;
        $this->ddd = (string)$dddSanitized;
        $this->ddi = (string)$ddiSanitized;
    }

    public static function createFromPieces(string $phone, string $ddd, string $ddi = '+55'): Phone
    {
        return new static($phone, $ddd, $ddi);
    }

    public static function createFromDddAndPhone(string $phoneWithDdd): Phone
    {
        $ddd = substr($phoneWithDdd, 0, 2);
        $phone = trim(substr($phoneWithDdd, 2, 9));

        return new static($phone, $ddd);
    }

    /**
     * @return bool
     */
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

    private function checkIfPhoneIsEmptyOrNull(string $phone)
    {
        if (is_null($phone) || empty($phone)) {
            throw new InvalidArgumentException("Phone number cannot be empty or null.");
        }
    }

    private function checkIfDDDIsEmptyOrNull(string $ddd)
    {
        if (is_null($ddd) || empty($ddd)) {
            throw new InvalidArgumentException("DDD cannot be empty or null.");
        }
    }

    private function checkPhoneHas8Or9Chars(string $phone): void
    {
        if (strlen($phone) < 8 || strlen($phone) > 9) {
            throw new InvalidArgumentException('Phone "' . $phone . '" number must be 8 or 9 digits');
        }
    }

    private function checkIfDDDHasTwoChars(string $ddd): void
    {
        if (strlen($ddd) !== 2) {
            throw new InvalidArgumentException('DDD must be only 2 characters.');
        }
    }

    private function checkIfDDDIsNumeric(string $ddd): void
    {
        if (!is_numeric($ddd)) {
            throw new InvalidArgumentException('DDD must be numeric.');
        }
    }

    private function checkIfDDIHasTwoChars(string $ddi): void
    {
        if (strlen($ddi) !== 2) {
            throw new InvalidArgumentException('DDI must be only 2 characters.');
        }
    }

    private function checkIfDDIIsNumeric(string $ddi): void
    {
        if (!is_numeric($ddi)) {
            throw new InvalidArgumentException('DDI must be numeric.');
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
