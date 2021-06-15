<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidDomainParamException;
use App\Shared\Domain\Exceptions\InvalidUrlException;
use App\Shared\Domain\ValueObjectBase;

final class Url extends ValueObjectBase
{
    private string $url;

    /**
     * @throws InvalidUrlException
     * @throws InvalidDomainParamException
     */
    public function __construct(string $url)
    {
        if (empty($url)) {
            throw new InvalidDomainParamException('URL is empty.');
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException($url);
        }

        $this->url = $url;
    }

    public function value()
    {
        return $this->url;
    }
}
