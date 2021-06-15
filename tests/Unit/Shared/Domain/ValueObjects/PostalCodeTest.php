<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidPostalCodeException;
use App\Shared\Domain\ValueObjects\PostalCode;
use Tests\TestCase;

class PostalCodeTest extends TestCase
{
    public function testIfExceptionIsThrownWhenProvidedPostalCodeIsEmpty()
    {
        $this->expectException(InvalidPostalCodeException::class);
        $this->expectExceptionMessage('Postal code cannot be empty');

        new PostalCode('');
    }

    public function testIfExceptionIsThrownWhenProvidedPostalCodeIsNotDigit()
    {
        $this->expectException(InvalidPostalCodeException::class);
        $this->expectExceptionMessage('The postal code must contain numbers only');

        new PostalCode('11.13z-9aa');
    }

    public function testIfExceptionIsThrownWhenProvidedPostalCodeIsLessThanEightChars()
    {
        $this->expectException(InvalidPostalCodeException::class);
        $this->expectExceptionMessage('Postal code must be exactly 8 characters long');

        new PostalCode('1111');
    }

    public function testIfExceptionIsThrownWhenProvidedPostalCodeIsGreaterThanEightChars()
    {
        $this->expectException(InvalidPostalCodeException::class);
        $this->expectExceptionMessage('Postal code must be exactly 8 characters long');

        new PostalCode('1111111111');
    }

    public function testIfPostalCodeIsReturnedCorrectly()
    {
        $postalCode = new PostalCode('60000000');
        $postalCodeFormatted = new PostalCode('70.000-000');

        $this->assertSame('60000000', $postalCode->value());
        $this->assertSame('60000000', (string)$postalCode);
        $this->assertSame('60000-000', $postalCode->formatted());

        $this->assertSame('70000000', $postalCodeFormatted->value());
        $this->assertSame('70000000', (string)$postalCodeFormatted);
        $this->assertSame('70000-000', $postalCodeFormatted->formatted());
    }
}
