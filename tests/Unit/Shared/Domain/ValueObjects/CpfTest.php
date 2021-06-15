<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidCpfException;
use App\Shared\Domain\ValueObjects\Cpf;
use Tests\TestCase;

class CpfTest extends TestCase
{
    public function testIfExceptionIsThrownWhenCpfIsEmpty()
    {
        $this->expectException(InvalidCpfException::class);
        $this->expectExceptionMessage('CPF cannot be empty');

        new Cpf('');
    }

    public function testIfExceptionIsThrownWhenProvidedCpfIsNotDigit()
    {
        $this->expectException(InvalidCpfException::class);
        $this->expectExceptionMessage('The CPF must contain numbers only');

        new Cpf('11r.13z.9aa-z4');
    }

    public function testIfExceptionIsThrownWhenIsProvidedCpfLessThan11Characters()
    {
        $this->expectException(InvalidCpfException::class);
        $this->expectExceptionMessage('CPF should be 11 characters.');

        new Cpf('11111');
    }

    public function testIfExceptionIsThrownWhenIsProvidedCpfGreaterThan11Characters()
    {
        $this->expectException(InvalidCpfException::class);
        $this->expectExceptionMessage('CPF should be 11 characters.');

        new Cpf('1111111111111');
    }

    public function testIfExceptionIsThrownWhenInvalidCpfIsProvided()
    {
        $this->expectException(InvalidCpfException::class);
        $this->expectExceptionMessage('Invalid CPF.');

        new Cpf('54545454545');
    }

    public function testIfCpfIsCreatedWithValidValue()
    {
        $cpf = new Cpf('96471813023');
        $cpfFormatted = new Cpf('107.825.810-48');

        $this->assertSame('96471813023', $cpf->value());
        $this->assertSame('964.718.130-23', $cpf->formatted());
        $this->assertSame('96471813023', (string)$cpf);

        $this->assertSame('10782581048', $cpfFormatted->value());
        $this->assertSame('107.825.810-48', $cpfFormatted->formatted());
        $this->assertSame('10782581048', (string)$cpfFormatted);
    }
}
