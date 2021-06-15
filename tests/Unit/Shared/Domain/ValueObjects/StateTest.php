<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidDomainParamException;
use App\Shared\Domain\Exceptions\InvalidStateException;
use App\Shared\Domain\ValueObjects\State;
use Tests\TestCase;

class StateTest extends TestCase
{
    public function testIfExceptionIsThrownWhenProvidedStateIsEmpty()
    {
        $this->expectException(InvalidDomainParamException::class);
        $this->expectExceptionMessage('UF cannot be empty');

        new State('');
    }

    public function testIfExceptionIsThrownWhenProvidedStateIsLessThanTwoChars()
    {
        $this->expectException(InvalidDomainParamException::class);
        $this->expectExceptionMessage('UF must be exactly 2 characters long');

        new State('a');
    }

    public function testIfExceptionIsThrownWhenProvidedStateIsGreaterThanTwoChars()
    {
        $this->expectException(InvalidDomainParamException::class);
        $this->expectExceptionMessage('UF must be exactly 2 characters long');

        new State('any-uf');
    }

    public function testIfExceptionIsThrownWhenProvidedStateIsInvalid()
    {
        $this->expectException(InvalidStateException::class);
        $this->expectExceptionMessage("Invalid state 'ZX'");

        new State('zx');
    }

    public function testIfStateIsReturnedCorrectly()
    {
        $ce = new State('ce');
        $sp = new State('sp');

        $this->assertSame('CE', $ce->value());
        $this->assertSame('CE', (string)$ce);
        $this->assertSame('Ceará', $ce->name());
        $this->assertSame('CEARÁ', $ce->name(true));

        $this->assertSame('SP', $sp->value());
        $this->assertSame('SP', (string)$sp);
        $this->assertSame('São Paulo', $sp->name());
        $this->assertSame('SÃO PAULO', $sp->name(true));
    }
}
