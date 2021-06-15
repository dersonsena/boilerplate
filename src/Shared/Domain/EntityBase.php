<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Contracts\Entity;
use App\Shared\Domain\Exceptions\InvalidDomainParamException;
use DateTimeInterface;
use ReflectionClass;
use ReflectionException;

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
     * @throws InvalidDomainParamException
     */
    private function __construct(array $values)
    {
        $this->fill($values);
    }

    /**
     * Static method to create an Entity
     * @param array $values
     * @return EntityBase
     * @throws InvalidDomainParamException
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
     * @throws InvalidDomainParamException
     */
    public function fill(array $values): void
    {
        foreach ($values as $attribute => $value) {
            $this->set($attribute, $value);
        }
    }

    /**
     * @inheritDoc
     * @throws InvalidDomainParamException
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
            throw new InvalidDomainParamException(
                "it couldn't change the value of property '{$property}' because it doesn't exist in Entity Class '" . get_class() . "'",
                ['property' => $property, 'value' => $value]
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
     * @throws ReflectionException
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
    /*public function validationRules(): array
    {
        return [];
    }*/

    /**
     * Magic getter method to get an Entity property value
     * @param string $name
     * @return mixed
     * @throws InvalidDomainParamException
     */
    public function __get(string $name)
    {
        $getter = "get" . ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }

        if (!property_exists($this, $name)) {
            throw new InvalidDomainParamException(
                "you cannot get the property '{$name}' because it doesn't exist in the Entity Class '" . get_class() . "'",
                ['property' => $name]
            );
        }

        return $this->{$name};
    }

    /**
     * @throws InvalidDomainParamException
     */
    public function __set($name, $value)
    {
        throw new InvalidDomainParamException(
            "you cannot change the property '{$name}' of the Entity Class '" . get_class() . "' because it is read-only.",
            ['property' => $name, 'value' => $value]
        );
    }
}
