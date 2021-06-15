<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use App\Shared\Domain\ValueObjects\Address;
use App\Shared\Domain\ValueObjects\PostalCode;
use App\Shared\Domain\ValueObjects\State;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function testIfAddressIsCreatedCorrectly()
    {
        $street = self::$faker->streetAddress;
        $number = (string)self::$faker->randomNumber();
        $district = self::$faker->words(3, true);
        $city = self::$faker->words(3, true);
        $state = new State('CE');
        $postalCode = new PostalCode('60000-000');
        $complement = self::$faker->sentence();
        $sameMailing = true;
        $value = trim("{$street}, nº {$number} {$complement} - {$district}. {$city} - {$state}. CEP {$postalCode}");

        $sut = new Address($street, $number, $district, $city, $state, $postalCode, $complement, $sameMailing);

        $this->assertSame($street, $sut->getStreet());
        $this->assertSame($number, $sut->getNumber());
        $this->assertSame($district, $sut->getDistrict());
        $this->assertSame($city, $sut->getCity());
        $this->assertSame($state, $sut->getState());
        $this->assertSame($postalCode, $sut->getPostalCode());
        $this->assertSame($complement, $sut->getComplement());
        $this->assertTrue($sut->isSameMailingAddress());
        $this->assertSame($value, $sut->value());
    }
}
