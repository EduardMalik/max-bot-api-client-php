<?php

declare(strict_types=1);

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
    public function itCorrectlyCastsArrayOfEnums(): void
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
    public function itCorrectlyCastsArrayOfModels(): void
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
    public function itReturnsRawArrayWhenAttributeIsMissing(): void
    {
        $result = DummyModelForMapping::fromArray(['untyped_array' => ['a', 'b', 'c']]);
        $this->assertIsArray($result->untypedArray);
        $this->assertSame(['a', 'b', 'c'], $result->untypedArray);
    }
    public function itHandlesEmptyArraysCorrectly(): void
    {
        $result = DummyModelForMapping::fromArray([
            'name' => 'Test with Empty',
            'update_types' => [],
        ]);
        $this->assertIsArray($result->updateTypes);
        $this->assertEmpty($result->updateTypes);
    }
    public function itHandlesNullForNullableArray(): void
    {
        $result = DummyModelForMapping::fromArray(['child_models' => null]);
        $this->assertNull($result->childModels);
    }
    public function itCorrectlyCastsInteger(): void
    {
        $result = DummyModelForMapping::fromArray(['untyped_int' => 123]);
        $this->assertIsInt($result->untypedInt);
        $this->assertSame(123, $result->untypedInt);
    }
    public function itCorrectlyCastsFloat(): void
    {
        $result = DummyModelForMapping::fromArray(['untyped_float' => 1.23]);
        $this->assertIsFloat($result->untypedFloat);
        $this->assertSame(1.23, $result->untypedFloat);
    }
    public function itCorrectlyCastsBoolean(): void
    {
        $result = DummyModelForMapping::fromArray(['untyped_bool' => true]);
        $this->assertIsBool($result->untypedBool);
        $this->assertTrue($result->untypedBool);
    }
    public function itReturnsValueAsIsForUnmanagedObjectType(): void
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
    public function castArrayHandlesNonArrayValueForArrayProperty(): void
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
    public function castArrayReturnsArrayAsIsForUnmanagedObjectTypesInArrayOf(): void
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
    public function toArraySkipsUninitializedProperties(): void
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
    public function castValueReturnsValueAsIsForUnmanagedScalarAssignedToObjectType(): void
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
    public function __construct(?string $name, ?array $tags)
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
    public function __construct(
        ?string $name,
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\stdClass::class)]
        ?array $items
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
    public function __construct(?string $name, ?stdClass $externalObject)
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
    public function __construct(
        ?string $name,
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\BushlanovDev\MaxMessengerBot\Enums\UpdateType::class)]
        ?array $updateTypes,
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\BushlanovDev\MaxMessengerBot\Tests\Models\DummyChildModel::class)]
        ?array $childModels,
        ?array $untypedArray,
        ?int $untypedInt,
        ?float $untypedFloat,
        ?bool $untypedBool
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
    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
