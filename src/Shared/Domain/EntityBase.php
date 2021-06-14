<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Contracts\Entity;
use DateTimeInterface;
use InvalidArgumentException;
use ReflectionClass;

/**
 * Class Entity
 * @package App\Shared\Domain
 * @author Kilderson Sena <dersonsena@gmail.com>
 *
 * @property-read int|string $id
 */
abstract class EntityBase implements Entity
{
    /**
     * @var string|int
     */
    protected $id;

    /**
     * Entity constructor.
     * @param array $values
     * @throws InvalidArgumentException if any property doesn't exists
     */
    private function __construct(array $values)
    {
        $this->fill($values);
    }

    /**
     * Static method to create an Entity
     * @param array $values
     * @return EntityBase
     */
    public static function create(array $values): Entity
    {
        return new static($values);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function fill(array $values): void
    {
        foreach ($values as $attribute => $value) {
            $this->set($attribute, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function set(string $property, $value): Entity
    {
        if (mb_strstr($property, '_') !== false) {
            $property = lcfirst(str_replace('_', '', ucwords($property, '_')));
        }

        $setter = 'set' . str_replace('_', '', ucwords($property, '_'));

        if (method_exists($this, $setter)) {
            $this->{$setter}($value);
            return $this;
        }

        if (!property_exists($this, $property)) {
            throw new InvalidArgumentException(
                "Property '{$property}' doesn't exists in Entity Class '" . get_class() . "'"
            );
        }

        $this->{$property} = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $property)
    {
        return $this->{$property};
    }

    /**
     * @inheritDoc
     */
    public function toArray(bool $toSnakeCase = false): array
    {
        $props = [];
        $propertyList = get_object_vars($this);

        foreach ($propertyList as $prop => $value) {
            if ($value instanceof DateTimeInterface) {
                $propertyList[$prop] = $value->format(DATE_ATOM);
                continue;
            }

            if (is_object($value)) {
                $reflectObject = new ReflectionClass(get_class($value));
                $properties = $reflectObject->getProperties();
                $propertyList[$prop] = [];

                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $propertyList[$prop][$property->getName()] = $property->getValue($value);
                }
            }
        }

        $propertyList = json_decode(json_encode($propertyList), true);

        foreach ($propertyList as $name => $value) {
            if ($toSnakeCase) {
                $name = mb_strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
            }

            $props[$name] = $value;
        }

        return $props;
    }

    /**
     * Method used to create the entity validation. An example to create a validation:
     *
     * ```php
     * return [
     *     ...ValidationBuilder::field('property_name1')->required()->maxLength(25)->build(),
     *     ...ValidationBuilder::field('property_name2')->minLength(25)->build()
     *     ...ValidationBuilder::field('property_name3')->email()->build()
     * ]
     * ```
     *
     * @return array List of validations settings to be used in validation process
     */
    public function validationRules(): array
    {
        return [];
    }

    /**
     * Magic getter method to get an Entity property value
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
                "Property '{$name}' doesn't exists in Entity Class '" . get_class() . "'"
            );
        }

        return $this->{$name};
    }

    public function __set($name, $value)
    {
        throw new InvalidArgumentException(
            "The '{$name}' property of the '" . get_class() . "' Entity Class is read-only."
        );
    }
}
