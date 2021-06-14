<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\ValueObjectBase;
use InvalidArgumentException;

final class Url extends ValueObjectBase
{
    private string $url;

    public function __construct(string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid URL: ' . $url);
        }

        $this->url = $url;
    }

    public function value()
    {
        return $this->url;
    }
}
