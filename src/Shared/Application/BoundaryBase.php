<?php

declare(strict_types=1);

namespace App\Shared\Application;

use App\Shared\Application\Contracts\Boundary;
use App\Shared\Application\Exceptions\InvalidUseCaseParamException;

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
     * @throws InvalidUseCaseParamException
     */
    private function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            if (mb_strstr($key, '_') !== false) {
                $key = lcfirst(str_replace('_', '', ucwords($key, '_')));
            }

            if (!property_exists($this, $key)) {
                throw new InvalidUseCaseParamException(
                    "it couldn't construct boundary '" . get_class() . "' because the property '{$key}' doesn't exist",
                    ['property' => $key]
                );
            }

            $this->{$key} = $value;
            $this->boundaryValues[$key] = $this->get($key);
        }
    }

    /**
     * Static method to create a Boundary (Input or Output)
     * @param array $values Associative array such as `'property' => 'value'`
     * @throws InvalidUseCaseParamException
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
     * @throws InvalidUseCaseParamException
     */
    public function get(string $property)
    {
        return $this->__get($property);
    }

    /**
     * Magic getter method to get a Boundary property value
     * @param string $name
     * @return mixed
     * @throws InvalidUseCaseParamException
     */
    public function __get(string $name)
    {
        $getter = "get" . ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }

        if (!property_exists($this, $name)) {
            throw new InvalidUseCaseParamException(
                "it couldn't get the property '{$name}' because it doesn't exist in the Boundary Class '" . get_class() . "'",
                ['property' => $name]
            );
        }

        return $this->{$name};
    }

    /**
     * @throws InvalidUseCaseParamException
     */
    public function __set($name, $value)
    {
        throw new InvalidUseCaseParamException(
            "you cannot change the property '{$name}' of the Boundary Class '" . get_class() . "' because it is read-only.",
            ['property' => $name, 'value' => $value]
        );
    }
}
