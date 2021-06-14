<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Contracts\ValueObject;

abstract class ValueObjectBase implements ValueObject
{
    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->value();
    }

    /**
     * @inheritDoc
     */
    public function isEqualsTo(ValueObject $valueObject): bool
    {
        return $this->objectHash() === $valueObject->objectHash();
    }

    /**
     * @inheritDoc
     */
    public function objectHash(): string
    {
        $props = get_class_vars(get_class($this));
        $value = '';

        foreach ($props as $value) {
            $value .= $value;
        }

        return md5($value);
    }
}
