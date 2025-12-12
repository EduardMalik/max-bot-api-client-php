<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

final class AbstractModelMappingTest extends TestCase
{
    /**
     * @return void
     */
    public function itCorrectlyCastsArrayOfEnums()
    {
        $result = DummyModelForMapping::fromArray([
            'name' => 'Test With Enums',
            'update_types' => ['message_created', 'bot_started'],
        ]);
        $this->assertInstanceOf(DummyModelForMapping::class, $result);
        $this->assertIsArray($result->updateTypes);
        $this->assertCount(2, $result->updateTypes);
        $this->assertInstanceOf(UpdateType::class, $result->updateTypes[0]);
        $this->assertSame(UpdateType::MessageCreated, $result->updateTypes[0]);
        $this->assertSame(UpdateType::BotStarted, $result->updateTypes[1]);
    }
    /**
     * @return void
     */
    public function itCorrectlyCastsArrayOfModels()
    {
        $result = DummyModelForMapping::fromArray([
            'name' => 'Test With Models',
            'child_models' => [
                ['value' => 'child 1'],
                ['value' => 'child 2'],
            ],
        ]);
        $this->assertIsArray($result->childModels);
        $this->assertCount(2, $result->childModels);
        $this->assertInstanceOf(DummyChildModel::class, $result->childModels[0]);
        $this->assertSame('child 1', $result->childModels[0]->value);
    }
    /**
     * @return void
     */
    public function itReturnsRawArrayWhenAttributeIsMissing()
    {
        $result = DummyModelForMapping::fromArray(['untyped_array' => ['a', 'b', 'c']]);
        $this->assertIsArray($result->untypedArray);
        $this->assertSame(['a', 'b', 'c'], $result->untypedArray);
    }
    /**
     * @return void
     */
    public function itHandlesEmptyArraysCorrectly()
    {
        $result = DummyModelForMapping::fromArray([
            'name' => 'Test with Empty',
            'update_types' => [],
        ]);
        $this->assertIsArray($result->updateTypes);
        $this->assertEmpty($result->updateTypes);
    }
    /**
     * @return void
     */
    public function itHandlesNullForNullableArray()
    {
        $result = DummyModelForMapping::fromArray(['child_models' => null]);
        $this->assertNull($result->childModels);
    }
    /**
     * @return void
     */
    public function itCorrectlyCastsInteger()
    {
        $result = DummyModelForMapping::fromArray(['untyped_int' => 123]);
        $this->assertIsInt($result->untypedInt);
        $this->assertSame(123, $result->untypedInt);
    }
    /**
     * @return void
     */
    public function itCorrectlyCastsFloat()
    {
        $result = DummyModelForMapping::fromArray(['untyped_float' => 1.23]);
        $this->assertIsFloat($result->untypedFloat);
        $this->assertSame(1.23, $result->untypedFloat);
    }
    /**
     * @return void
     */
    public function itCorrectlyCastsBoolean()
    {
        $result = DummyModelForMapping::fromArray(['untyped_bool' => true]);
        $this->assertIsBool($result->untypedBool);
        $this->assertTrue($result->untypedBool);
    }
    /**
     * @return void
     */
    public function itReturnsValueAsIsForUnmanagedObjectType()
    {
        $externalObject = new stdClass();
        $externalObject->data = 'some value';
        $rawData = [
            'name' => 'Test With External Object',
            'external_object' => $externalObject,
        ];
        $result = ModelWithExternalObject::fromArray($rawData);
        $this->assertInstanceOf(ModelWithExternalObject::class, $result);
        $this->assertSame($externalObject, $result->externalObject);
        $this->assertSame('some value', $result->externalObject->data);
    }
    /**
     * @return void
     */
    public function castArrayHandlesNonArrayValueForArrayProperty()
    {
        $rawData = [
            'name' => 'Test with scalar instead of array',
            'tags' => 'single_tag_value',
        ];
        $result = DummyModelForArrayCast::fromArray($rawData);
        $this->assertInstanceOf(DummyModelForArrayCast::class, $result);
        $this->assertIsArray($result->tags);
        $this->assertSame(['single_tag_value'], $result->tags);
    }
    /**
     * @return void
     */
    public function castArrayReturnsArrayAsIsForUnmanagedObjectTypesInArrayOf()
    {
        $items = [
            (object)['id' => 1, 'name' => 'Item A'],
            (object)['id' => 2, 'name' => 'Item B'],
        ];
        $rawData = [
            'name' => 'Test with unmanaged objects',
            'items' => $items,
        ];
        $result = ModelWithUnmanagedArray::fromArray($rawData);
        $this->assertInstanceOf(ModelWithUnmanagedArray::class, $result);
        $this->assertIsArray($result->items);
        $this->assertSame($items, $result->items);
        $this->assertSame('Item A', $result->items[0]->name);
    }
    /**
     * @return void
     */
    public function toArraySkipsUninitializedProperties()
    {
        $reflection = new \ReflectionClass(DummyModelForUninitializedProperty::class);
        $instance = $reflection->newInstanceWithoutConstructor();
        $initializedProp = $reflection->getProperty('initializedProp');
        $initializedProp->setValue($instance, 'I have a value');
        $resultArray = $instance->toArray();
        $expectedArray = [
            'initialized_prop' => 'I have a value',
        ];
        $this->assertEquals($expectedArray, $resultArray);
        $this->assertArrayNotHasKey('uninitialized_prop', $resultArray);
    }
    /**
     * @return void
     */
    public function castValueReturnsValueAsIsForUnmanagedScalarAssignedToObjectType()
    {
        $dateString = '2025-08-26';
        $reflectionClass = new ReflectionClass(ModelForUnmanagedType::class);
        $abstractModelReflection = $reflectionClass->getParentClass();
        $castValueMethod = $abstractModelReflection->getMethod('castValue');
        $castValueMethod->setAccessible(true);
        $property = $reflectionClass->getProperty('eventDate');
        $result = $castValueMethod->invoke(null, $dateString, $property);
        $this->assertSame($dateString, $result);
    }
}

final class ModelForUnmanagedType extends AbstractModel
{
    /**
     * @readonly
     * @var \DateTimeInterface
     */
    public $eventDate;
    public function __construct(DateTimeInterface $eventDate)
    {
        $this->eventDate = $eventDate;
    }
}

final class DummyModelForUninitializedProperty extends AbstractModel
{
    /**
     * @readonly
     * @var string
     */
    public $initializedProp;
    /**
     * @readonly
     * @var int
     */
    public $uninitializedProp;
}

final class DummyModelForArrayCast extends AbstractModel
{
    /**
     * @readonly
     * @var string|null
     */
    public $name;
    /**
     * @readonly
     * @var mixed[]|null
     */
    public $tags;
    /**
     * @param string|null $name
     * @param mixed[]|null $tags
     */
    public function __construct($name, $tags)
    {
        $this->name = $name;
        $this->tags = $tags;
    }
}

final class ModelWithUnmanagedArray extends AbstractModel
{
    /**
     * @readonly
     * @var string|null
     */
    public $name;
    /**
     * @readonly
     * @var mixed[]|null
     */
    public $items;
    /**
     * @param string|null $name
     * @param mixed[]|null $items
     */
    public function __construct(
        $name,
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\stdClass::class)]
        $items
    )
    {
        $this->name = $name;
        $this->items = $items;
    }
}

final class ModelWithExternalObject extends AbstractModel
{
    /**
     * @readonly
     * @var string|null
     */
    public $name;
    /**
     * @readonly
     * @var \stdClass|null
     */
    public $externalObject;
    /**
     * @param string|null $name
     * @param \stdClass|null $externalObject
     */
    public function __construct($name, $externalObject)
    {
        $this->name = $name;
        $this->externalObject = $externalObject;
    }
}

final class DummyModelForMapping extends AbstractModel
{
    /**
     * @readonly
     * @var string|null
     */
    public $name;
    /**
     * @readonly
     * @var mixed[]|null
     */
    public $updateTypes;
    /**
     * @readonly
     * @var mixed[]|null
     */
    public $childModels;
    /**
     * @readonly
     * @var mixed[]|null
     */
    public $untypedArray;
    /**
     * @readonly
     * @var int|null
     */
    public $untypedInt;
    /**
     * @readonly
     * @var float|null
     */
    public $untypedFloat;
    /**
     * @readonly
     * @var bool|null
     */
    public $untypedBool;
    /**
     * @param string|null $name
     * @param mixed[]|null $updateTypes
     * @param mixed[]|null $childModels
     * @param mixed[]|null $untypedArray
     * @param int|null $untypedInt
     * @param float|null $untypedFloat
     * @param bool|null $untypedBool
     */
    public function __construct(
        $name,
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\BushlanovDev\MaxMessengerBot\Enums\UpdateType::class)]
        $updateTypes,
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\BushlanovDev\MaxMessengerBot\Tests\Models\DummyChildModel::class)]
        $childModels,
        $untypedArray,
        $untypedInt,
        $untypedFloat,
        $untypedBool
    )
    {
        $this->name = $name;
        $this->updateTypes = $updateTypes;
        $this->childModels = $childModels;
        $this->untypedArray = $untypedArray;
        $this->untypedInt = $untypedInt;
        $this->untypedFloat = $untypedFloat;
        $this->untypedBool = $untypedBool;
    }
}

final class DummyChildModel extends AbstractModel
{
    /**
     * @readonly
     * @var string
     */
    public $value;
    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $value = (string) $value;
        $this->value = $value;
    }
}
