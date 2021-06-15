<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidDomainParamException;
use App\Shared\Domain\Exceptions\InvalidGenderException;
use App\Shared\Domain\ValueObjects\Gender;
use Tests\TestCase;

class GenderTest extends TestCase
{
    public function testIfExceptionIsThrownWhenGenderIsEmpty()
    {
        $this->expectException(InvalidDomainParamException::class);
        $this->expectExceptionMessage('Gender is empty.');

        new Gender('');
    }

    public function testIfExceptionIsThrownWhenGenderIsInvalid()
    {
        $gender = self::$faker->word;

        $this->expectException(InvalidGenderException::class);
        $this->expectExceptionMessage("Invalid gender '{$gender}'");

        new Gender($gender);
    }

    public function testIfGenderIsCreatedCorrectly()
    {
        $genderMale = new Gender(Gender::MALE);
        $genderFemale = new Gender(Gender::FEMALE);

        $this->assertSame(Gender::MALE, $genderMale->value());
        $this->assertSame(Gender::MALE, (string)$genderMale);
        $this->assertSame(Gender::FEMALE, $genderFemale->value());
        $this->assertSame(Gender::FEMALE, (string)$genderFemale);
    }
}
