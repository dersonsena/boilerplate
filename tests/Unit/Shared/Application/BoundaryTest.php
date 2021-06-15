<?php

namespace Tests\Unit\Shared\Application;

use App\Shared\Application\BoundaryBase;
use App\Shared\Application\Contracts\Boundary;
use App\Shared\Application\Exceptions\InvalidUseCaseParamException;
use Tests\TestCase;
use Tests\Unit\Shared\Application\Fakes\AnyBoundary;
use TypeError;

class BoundaryTest extends TestCase
{
    public static function makeSut(array $values = []): Boundary
    {
        if (!empty($values)) {
            return AnyBoundary::create($values);
        }

        return AnyBoundary::create([
            'stringProp' => self::$faker->words(2, true),
            'nullableProp' => null,
            'intProp' => self::$faker->randomDigit(),
            'floatProp' => self::$faker->randomFloat(),
            'arrayProp' => ['a', 'b', 'c', 'd'],
        ]);
    }

    public function testIfExceptionIsThrownWhenBoundaryPropertyDoesNotExists()
    {
        $this->expectException(InvalidUseCaseParamException::class);
        $this->expectExceptionMessage("Invalid use case param: it couldn't construct boundary '". BoundaryBase::class ."' because the property 'invalidProp' doesn't exist");

        self::makeSut([
            'invalidProp' => self::$faker->words(2, true)
        ]);
    }

    public function testIfExceptionIsThrownWhenNonexistentPropertyIsCalled()
    {
        $this->expectException(InvalidUseCaseParamException::class);
        $this->expectExceptionMessage("Invalid use case param: it couldn't get the property 'invalidProp' because it doesn't exist in the Boundary Class '". BoundaryBase::class ."'");

        $sut = self::makeSut();

        /** @noinspection PhpExpressionResultUnusedInspection */
        $sut->invalidProp;
    }

    public function testIfNullableBoundaryPropertyIsNotFilledWhenNullValueIsProvided()
    {
        $sut = self::makeSut(['nullableProp' => null]);
        $this->assertSame(null, $sut->nullableProp);
    }

    public function testIfExceptionIsThrownWhenValueIsNullToNoNullableProperty()
    {
        $this->expectException(TypeError::class);

        $sut = self::makeSut(['intProp' => null]);
        $this->assertSame(null, $sut->intProp);
    }

    public function testIfPropertiesWithUnderscoreSeparatorWillPopulateBoundary()
    {
        $values = [
            'string_prop' => self::$faker->words(2, true),
            'nullable_prop' => self::$faker->words(2, true),
            'int_prop' => self::$faker->randomDigit(),
            'float_prop' => self::$faker->randomFloat(),
            'array_prop' => ['a', 'b', 'c', 'd']
        ];

        $sut = self::makeSut($values);

        $this->assertSame(strtoupper($values['string_prop']), $sut->stringProp);
        $this->assertSame($values['nullable_prop'], $sut->nullableProp);
        $this->assertSame($values['int_prop'], $sut->intProp);
        $this->assertSame($values['float_prop'], $sut->floatProp);
        $this->assertSame($values['array_prop'], $sut->arrayProp);
    }

    public function testIfCorrectValuesAreReturned()
    {
        $boundaryValues = [
            'stringProp' => self::$faker->words(2, true),
            'nullableProp' => self::$faker->words(2, true),
            'intProp' => self::$faker->randomDigit(),
            'floatProp' => self::$faker->randomFloat(),
            'arrayProp' => ['a', 'b', 'c', 'd']
        ];

        $sut = self::makeSut($boundaryValues);
        $values = $sut->values();

        $this->assertSame($values['stringProp'], strtoupper($boundaryValues['stringProp']));
        $this->assertSame($values['nullableProp'], $boundaryValues['nullableProp']);
        $this->assertSame($values['intProp'], $boundaryValues['intProp']);
        $this->assertSame($values['floatProp'], $boundaryValues['floatProp']);
        $this->assertSame($values['arrayProp'], $boundaryValues['arrayProp']);
    }
}
