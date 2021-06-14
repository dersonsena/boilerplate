<?php

declare(strict_types=1);

namespace App\Shared\Utils;

use InvalidArgumentException;

trait CollectionValidator
{
    public function validateCollection(
        array $collection,
        string $className,
        string $exceptionClassName = InvalidArgumentException::class
    ) {
        foreach ($collection as $item) {
            if (!$item instanceof $className) {
                throw new $exceptionClassName($item);
            }
        }
    }
}
