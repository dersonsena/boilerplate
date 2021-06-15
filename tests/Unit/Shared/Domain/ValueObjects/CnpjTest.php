<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidCnpjException;
use App\Shared\Domain\ValueObjects\Cnpj;
use Tests\TestCase;

class CnpjTest extends TestCase
{
    public function testIfExceptionIsThrownWhenCnpjIsEmpty()
    {
        $this->expectException(InvalidCnpjException::class);
        $this->expectExceptionMessage('CNPJ cannot be empty');

        new Cnpj('');
    }

    public function testIfExceptionIsThrownWhenProvidedCnpjIsNotDigit()
    {
        $this->expectException(InvalidCnpjException::class);
        $this->expectExceptionMessage('The CNPJ must contain numbers only');

        new Cnpj('134t67dxs357uh');
    }

    public function testIfExceptionIsThrownWhenIsProvidedCnpjLessThan11Characters()
    {
        $this->expectException(InvalidCnpjException::class);
        $this->expectExceptionMessage('CNPJ should be 14 characters.');

        new Cnpj('111111');
    }

    public function testIfExceptionIsThrownWhenIsProvidedCnpjGreaterThan11Characters()
    {
        $this->expectException(InvalidCnpjException::class);
        $this->expectExceptionMessage('CNPJ should be 14 characters.');

        new Cnpj('1111111111111111');
    }

    public function testIfExceptionIsThrownWhenInvalidCnpjIsProvided()
    {
        $this->expectException(InvalidCnpjException::class);
        $this->expectExceptionMessage('Invalid CNPJ.');

        new Cnpj('87876545678987');
    }

    public function testIfCnpjIsCreatedWithValidValue()
    {
        $cnpj = new Cnpj('41091205000107');
        $cnpjFormatted = new Cnpj('84.079.610/0001-12');

        $this->assertSame('41091205000107', $cnpj->value());
        $this->assertSame('41.091.205/0001-07', $cnpj->formatted());
        $this->assertSame('41091205000107', (string)$cnpj);

        $this->assertSame('84079610000112', $cnpjFormatted->value());
        $this->assertSame('84.079.610/0001-12', $cnpjFormatted->formatted());
        $this->assertSame('84079610000112', (string)$cnpjFormatted);
    }
}
