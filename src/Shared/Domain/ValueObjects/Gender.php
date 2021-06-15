<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidDomainParamException;
use App\Shared\Domain\Exceptions\InvalidGenderException;
use App\Shared\Domain\ValueObjectBase;
use App\Shared\Utils\EnumerationHelpers;

final class Gender extends ValueObjectBase
{
    use EnumerationHelpers;

    public const MALE = 'M';
    public const FEMALE = 'F';

    private string $gender;

    /**
     * @throws InvalidGenderException
     * @throws InvalidDomainParamException
     */
    public function __construct(string $gender)
    {
        if (empty($gender)) {
            throw new InvalidDomainParamException('Gender is empty.');
        }

        if (!self::hasValue($gender)) {
            throw new InvalidGenderException($gender);
        }

        $this->gender = $gender;
    }

    public function value()
    {
        return $this->gender;
    }
}
