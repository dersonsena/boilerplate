<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidDomainParamException;
use App\Shared\Domain\Exceptions\InvalidUrlException;
use App\Shared\Domain\ValueObjects\Url;
use Tests\TestCase;

class UrlTest extends TestCase
{
    public function testIfExceptionIsThrownWhenUrlIsEmpty()
    {
        $this->expectException(InvalidDomainParamException::class);
        $this->expectExceptionMessage('URL is empty.');

        new Url('');
    }

    public function testIfExceptionIsThrownWhenUrlIsInvalid()
    {
        $url = self::$faker->word;

        $this->expectException(InvalidUrlException::class);
        $this->expectExceptionMessage("Invalid URL '{$url}'");

        new Url($url);
    }

    public function testIfUrlIsCreatedCorrectly()
    {
        $value = self::$faker->url;
        $url = new Url($value);

        $this->assertSame($value, $url->value());
        $this->assertSame($value, (string)$url);
    }
}
