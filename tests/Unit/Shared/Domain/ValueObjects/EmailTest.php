<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidDomainParamException;
use App\Shared\Domain\Exceptions\InvalidEmailException;
use App\Shared\Domain\ValueObjects\Email;
use Tests\TestCase;

class EmailTest extends TestCase
{
    public function testIfExceptionIsThrownWhenEmailIsEmpty()
    {
        $this->expectException(InvalidDomainParamException::class);
        $this->expectExceptionMessage('E-mail is empty.');

        new Email('');
    }

    public function testIfExceptionIsThrownWhenEmailIsInvalid()
    {
        $email = self::$faker->word;

        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage("Invalid E-mail '{$email}'");

        new Email($email);
    }

    public function testIfEmailIsCreatedCorrectly()
    {
        $value = self::$faker->email;
        $email = new Email($value);

        $this->assertSame($value, $email->value());
        $this->assertSame($value, (string)$email);
    }

    public function testIfTwoEmailsAreEquals()
    {
        $value = self::$faker->email;
        $email1 = new Email($value);
        $email2 = new Email($value);

        $this->assertTrue($email1->isEqualsTo($email2));
    }

    public function testIfValueObjectHashIsCorrect()
    {
        $value = self::$faker->email;
        $email = new Email($value);

        $this->assertSame(md5($value), $email->objectHash());
    }
}
