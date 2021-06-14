<?php

declare(strict_types=1);

namespace App\Shared\Application;

use App\Shared\Application\Contracts\Boundary;
use InvalidArgumentException;

/**
 * Class Boundary
 * @package App\Shared\Application
 * @author Kilderson Sena <dersonsena@gmail.com>
 */
abstract class BoundaryBase implements Boundary
{
    private array $boundaryValues = [];

    /**
     * Boundary constructor.
     * @param array $values
     * @throws InvalidArgumentException if any property doesn't exists
     */
    private function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            if (mb_strstr($key, '_') !== false) {
                $key = lcfirst(str_replace('_', '', ucwords($key, '_')));
            }

            if (!property_exists($this, $key)) {
                throw new InvalidArgumentException(
                    "Property '{$key}' doesn't exists in Boundary Class '" . get_class() . "'"
                );
            }

            $this->{$key} = $value;
            $this->boundaryValues[$key] = $this->get($key);
        }
    }

    /**
     * Static method to create a Boundary (Input or Output)
     * @param array $values Associative array such as `'property' => 'value'`
     */
    public static function create(array $values): self
    {
        return new static($values);
    }

    /**
     * {@inheritdoc}
     */
    public function values(): array
    {
        return $this->boundaryValues;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $property)
    {
        return $this->__get($property);
    }

    /**
     * Magic getter method to get a Boundary property value
     * @param string $name
     * @return mixed
     * @throws InvalidArgumentException if any property doesn't exists
     */
    public function __get(string $name)
    {
        $getter = "get" . ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }

        if (!property_exists($this, $name)) {
            throw new InvalidArgumentException(
                "Property '{$name}' doesn't exists in Boundary Class '" . get_class() . "'"
            );
        }

        return $this->{$name};
    }

    public function __set($name, $value)
    {
        throw new InvalidArgumentException(
            "The '{$name}' property of the '" . get_class() . "' Boundary/DTO Class is read-only."
        );
    }
}
