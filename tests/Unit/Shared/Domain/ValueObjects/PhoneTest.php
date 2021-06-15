<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidPhoneException;
use App\Shared\Domain\ValueObjects\Phone;
use Tests\TestCase;

class PhoneTest extends TestCase
{
    public function testIfExceptionIsThrownWhenPhoneIsEmpty()
    {
        $this->expectException(InvalidPhoneException::class);
        $this->expectExceptionMessage('Phone number cannot be empty');

        Phone::createFromPieces('', '');
    }

    public function testIfExceptionIsThrownWhenDddIsEmpty()
    {
        $phone = (string)self::$faker->randomNumber(6, true);

        $this->expectException(InvalidPhoneException::class);
        $this->expectExceptionMessage('DDD cannot be empty or null');

        Phone::createFromPieces($phone, '');
    }

    public function testIfExceptionIsThrownWhenProvidedDddIsNotNumber()
    {
        $phone = (string)self::$faker->randomNumber(6, true);

        $this->expectException(InvalidPhoneException::class);
        $this->expectExceptionMessage('The DDD must contain numbers only');

        Phone::createFromPieces($phone, '1a');
    }

    public function testIfExceptionIsThrownWhenProvidedDddIsLessThan2Chars()
    {
        $phone = (string)self::$faker->randomNumber(6, true);
        $ddd = (string)self::$faker->randomNumber(1, true);

        $this->expectException(InvalidPhoneException::class);
        $this->expectExceptionMessage('DDD must be only 2 characters.');

        Phone::createFromPieces($phone, $ddd);
    }

    public function testIfExceptionIsThrownWhenProvidedDddIsGreaterThan2Chars()
    {
        $phone = (string)self::$faker->randomNumber(6, true);
        $ddd = (string)self::$faker->randomNumber(4, true);

        $this->expectException(InvalidPhoneException::class);
        $this->expectExceptionMessage('DDD must be only 2 characters.');

        Phone::createFromPieces($phone, $ddd);
    }

    public function testIfExceptionIsThrownWhenProvidedDdiIsNotNumber()
    {
        $phone = (string)self::$faker->randomNumber(6, true);
        $ddd = (string)self::$faker->randomNumber(2, true);

        $this->expectException(InvalidPhoneException::class);
        $this->expectExceptionMessage('The DDI must contain numbers only');

        Phone::createFromPieces($phone, $ddd, '3x');
    }

    public function testIfExceptionIsThrownWhenProvidedDdiIsLessThan2Chars()
    {
        $phone = (string)self::$faker->randomNumber(6, true);
        $ddd = (string)self::$faker->randomNumber(2, true);
        $ddi = (string)self::$faker->randomNumber(1, true);

        $this->expectException(InvalidPhoneException::class);
        $this->expectExceptionMessage('DDI must be only 2 characters.');

        Phone::createFromPieces($phone, $ddd, $ddi);
    }

    public function testIfExceptionIsThrownWhenProvidedDdiIsGreaterThan2Chars()
    {
        $phone = (string)self::$faker->randomNumber(6, true);
        $ddd = (string)self::$faker->randomNumber(2, true);
        $ddi = (string)self::$faker->randomNumber(5, true);

        $this->expectException(InvalidPhoneException::class);
        $this->expectExceptionMessage('DDI must be only 2 characters.');

        Phone::createFromPieces($phone, $ddd, $ddi);
    }

    public function testIfExceptionIsThrownWhenProvidedPhoneIsLessThanEightChars()
    {
        $phone = (string)self::$faker->randomNumber(6, true);
        $ddd = (string)self::$faker->randomNumber(2, true);

        $this->expectException(InvalidPhoneException::class);
        $this->expectExceptionMessage("Phone '{$phone}' number must be 8 or 9 digits");

        Phone::createFromPieces($phone, $ddd);
    }

    public function testIfExceptionIsThrownWhenProvidedPhoneIsGreaterThanNineChars()
    {
        $phone = '1234512345';
        $ddd = (string)self::$faker->randomNumber(2, true);

        $this->expectException(InvalidPhoneException::class);
        $this->expectExceptionMessage("Phone '{$phone}' number must be 8 or 9 digits");

        Phone::createFromPieces($phone, $ddd);
    }

    public function testIfPhoneIsCreatedWithCorrectValues()
    {
        $rawDdi = (string)self::$faker->randomNumber(2, true);
        $rawDdd = (string)self::$faker->randomNumber(2, true);
        $rawPhone = (string)self::$faker->randomNumber(9, true);

        $formattedDdi = '+55';
        $formattedDdd = '(85)';
        $formattedPhone = '9999-9999';

        $raw = Phone::createFromPieces($rawPhone, $rawDdd, $rawDdi);
        $formatted = Phone::createFromPieces($formattedPhone, $formattedDdd, $formattedDdi);

        $this->assertSame($rawDdi, $raw->getDdi());
        $this->assertSame($rawDdd, $raw->getDdd());
        $this->assertSame($rawDdi . $rawDdd . $rawPhone, $raw->value());
        $this->assertSame($rawDdd . $rawPhone, $raw->valueWithoutDdi());
        $this->assertSame($rawPhone, $raw->getPhone());
        $this->assertTrue($raw->isMobile());

        $this->assertSame('55', $formatted->getDdi());
        $this->assertSame('85', $formatted->getDdd());
        $this->assertSame('558599999999', $formatted->value());
        $this->assertSame('8599999999', $formatted->valueWithoutDdi());
        $this->assertSame('99999999', $formatted->getPhone());
        $this->assertFalse($formatted->isMobile());
    }

    public function testIfPhoneIsCreatedWithCorrectValuesWhenStringOfPhoneAndDddIsProvided()
    {
        $ddd = (string)self::$faker->randomNumber(2, true);
        $phone = (string)self::$faker->randomNumber(9, true);
        $phoneVo = Phone::createFromDddAndPhone($ddd . $phone);

        $this->assertSame('55', $phoneVo->getDdi());
        $this->assertSame($ddd, $phoneVo->getDdd());
        $this->assertSame('55' . $ddd . $phone, $phoneVo->value());
        $this->assertSame($ddd . $phone, $phoneVo->valueWithoutDdi());
    }
}
