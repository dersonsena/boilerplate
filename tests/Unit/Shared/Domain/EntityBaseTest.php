<?php

namespace Tests\Unit\Shared\Domain;

use App\Shared\Domain\EntityBase;
use App\Shared\Domain\Exceptions\InvalidDomainParamException;
use DateTimeImmutable;
use Tests\TestCase;
use Tests\Unit\Shared\Domain\Fakes\AnyEntity;
use Tests\Unit\Shared\Domain\Fakes\AnyValueObject;
use TypeError;

class EntityBaseTest extends TestCase
{
    public static function makeSut(array $values = []): EntityBase
    {
        if (!empty($values)) {
            return AnyEntity::create($values);
        }

        return AnyEntity::create([
            'stringProp' => self::$faker->words(2, true),
            'nullableProp' => null,
            'intProp' => self::$faker->randomDigit(),
            'floatProp' => self::$faker->randomFloat(),
            'arrayProp' => ['a', 'b', 'c', 'd'],
            'valueObjectProp' => new AnyValueObject('any value'),
            'datetimeProp' => new DateTimeImmutable('2021-06-14 21:00:00')
        ]);
    }

    public function testIfPropertyWithUnderscoreSeparatorWillPopulateEntity()
    {
        $values = [
            'id' => self::$faker->randomNumber(),
            'string_prop' => self::$faker->words(2, true),
            'nullable_prop' => self::$faker->words(2, true),
            'int_prop' => self::$faker->randomDigit(),
            'float_prop' => self::$faker->randomFloat(),
            'array_prop' => ['a', 'b', 'c', 'd'],
            'value_object_prop' => new AnyValueObject('any value')
        ];

        $sut = self::makeSut($values);

        $this->assertSame($values['id'], $sut->getId());
        $this->assertSame(strtoupper($values['string_prop']), $sut->stringProp);
        $this->assertSame(strtoupper($values['nullable_prop']), $sut->nullableProp);
        $this->assertSame($values['int_prop'], $sut->intProp);
        $this->assertSame($values['float_prop'], $sut->floatProp);
        $this->assertSame($values['array_prop'], $sut->arrayProp);
        $this->assertSame($values['value_object_prop'], $sut->valueObjectProp);
    }

    public function testIfGetMethodReturnRightValues()
    {
        $values = [
            'stringProp' => self::$faker->words(2, true),
            'nullableProp' => self::$faker->words(2, true),
        ];

        $sut = self::makeSut($values);

        $this->assertSame(strtoupper($values['stringProp']), $sut->get('stringProp'));
        $this->assertSame($values['nullableProp'], $sut->get('nullableProp'));
    }

    public function testIfDateTimePropertyIsFormattedToIsoPatternWhenToArrayIsCalled()
    {
        $values = [
            'datetimeProp' => new DateTimeImmutable('2021-06-14 21:00:00')
        ];

        $sut = self::makeSut($values);
        $data = $sut->toArray();

        $this->assertSame($values['datetimeProp']->format(DATE_ATOM), $data['datetimeProp']);
    }

    public function testIfExceptionIsThrownWhenPropertyDoesNotExists()
    {
        $this->expectException(InvalidDomainParamException::class);
        $this->expectExceptionMessage("Invalid domain param: it couldn't change the value of property 'invalidProp' because it doesn't exist in Entity '". EntityBase::class ."'");

        self::makeSut([
            'invalidProp' => self::$faker->words(2, true)
        ]);
    }

    public function testIfExceptionIsThrownWhenNonexistentPropertyIsCalled()
    {
        $this->expectException(InvalidDomainParamException::class);
        $this->expectExceptionMessage("Invalid domain param: you cannot get the property 'invalidProp' because it doesn't exist in the Entity '". EntityBase::class ."'");

        $sut = self::makeSut();

        /** @noinspection PhpExpressionResultUnusedInspection */
        $sut->invalidProp;
    }

    public function testIfNullablePropertyIsNotFilledWhenNullValueIsProvided()
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

    public function testIfValuesIsChangedWhenNewValuesIsProvided()
    {
        $sut = self::makeSut([
            'stringProp' => self::$faker->words(2, true),
            'nullableProp' => null,
            'intProp' => self::$faker->randomDigit(),
            'floatProp' => self::$faker->randomFloat(),
            'arrayProp' => ['a', 'b', 'c', 'd'],
            'valueObjectProp' => new AnyValueObject('any value')
        ]);

        $values = [
            'stringProp' => self::$faker->words(2, true),
            'nullableProp' => self::$faker->words(2, true),
            'intProp' => self::$faker->randomDigit(),
            'floatProp' => self::$faker->randomFloat(),
            'arrayProp' => ['a', 'b', 'c', 'd'],
            'valueObjectProp' => new AnyValueObject('any value')
        ];

        $sut->fill($values);

        $this->assertSame(strtoupper($values['stringProp']), $sut->stringProp);
        $this->assertSame(strtoupper($values['nullableProp']), $sut->nullableProp);
        $this->assertSame($values['intProp'], $sut->intProp);
        $this->assertSame($values['floatProp'], $sut->floatProp);
        $this->assertSame($values['arrayProp'], $sut->arrayProp);
        $this->assertSame($values['valueObjectProp'], $sut->valueObjectProp);
    }

    public function testIfValuesIsReturnedCorrectlyWhenToArrayIsPassedWithToSnakeCaseFlag()
    {
        $values = [
            'stringProp' => self::$faker->words(2, true),
            'nullableProp' => self::$faker->words(2, true),
            'intProp' => self::$faker->randomDigit(),
            'floatProp' => self::$faker->randomFloat(),
            'arrayProp' => ['a', 'b', 'c', 'd'],
            'valueObjectProp' => new AnyValueObject('any value')
        ];

        $sut = self::makeSut($values);
        $data = $sut->toArray(true);

        $this->assertArrayHasKey('string_prop', $data);
        $this->assertArrayHasKey('nullable_prop', $data);
        $this->assertArrayHasKey('int_prop', $data);
        $this->assertArrayHasKey('float_prop', $data);
        $this->assertArrayHasKey('array_prop', $data);
        $this->assertArrayHasKey('value_object_prop', $data);

        $this->assertSame(strtoupper($values['stringProp']), $data['string_prop']);
        $this->assertSame($values['nullableProp'], $data['nullable_prop']);
        $this->assertSame($values['intProp'], $data['int_prop']);
        $this->assertEquals($values['floatProp'], $data['float_prop']);
        $this->assertSame($values['arrayProp'], $data['array_prop']);

        $this->assertSame($values['valueObjectProp']->publicProp, $data['value_object_prop']['publicProp']);
    }

    public function testIfEntityIsReturnedToArray()
    {
        $values = [
            'stringProp' => self::$faker->words(2, true),
            'nullableProp' => self::$faker->words(2, true),
            'intProp' => self::$faker->randomDigit(),
            'floatProp' => self::$faker->randomFloat(),
            'arrayProp' => ['a', 'b', 'c', 'd'],
            'valueObjectProp' => new AnyValueObject('any value')
        ];

        $sut = self::makeSut($values);
        $array = $sut->toArray();

        $this->assertSame(strtoupper($values['stringProp']), $array['stringProp']);
        $this->assertSame($values['nullableProp'], $array['nullableProp']);
        $this->assertSame($values['intProp'], $array['intProp']);
        $this->assertSame($values['floatProp'], $array['floatProp']);
        $this->assertSame($values['arrayProp'], $array['arrayProp']);

        $this->assertSame('any public value', $array['valueObjectProp']['publicProp']);
        $this->assertSame('any protected value', $array['valueObjectProp']['protectedProp']);
        $this->assertSame('any value', $array['valueObjectProp']['privateProp']);
    }

    public function testIfExceptionIsThrownWhenEntityPropertyIsChangedManually()
    {
        $this->expectException(InvalidDomainParamException::class);
        $this->expectExceptionMessage("Invalid domain param: you cannot change the property 'stringProp' of the Entity '". EntityBase::class ."' because it is read-only.");

        $sut = self::makeSut();

        $sut->stringProp = self::$faker->words;
    }
}
