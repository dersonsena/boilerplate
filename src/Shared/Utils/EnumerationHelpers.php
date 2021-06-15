<?php

declare(strict_types=1);

namespace App\Shared\Utils;

use ReflectionClass;

trait EnumerationHelpers
{
    public static function constantList(): array
    {
        return (new ReflectionClass(__CLASS__))->getConstants();
    }

    public static function valuesList(): array
    {
        return array_values((new ReflectionClass(__CLASS__))->getConstants());
    }

    public static function constantNamesList(): array
    {
        return array_keys((new ReflectionClass(__CLASS__))->getConstants());
    }

    /**
     * @param string|int|float $value
     * @return bool
     */
    public static function hasValue($value): bool
    {
        return in_array($value, self::valuesList());
    }
}
