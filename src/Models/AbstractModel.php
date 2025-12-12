<?php

namespace BushlanovDev\MaxMessengerBot\Models;


use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\KeyboardPayload;
use BushlanovDev\MaxMessengerBot\Models\Message;
use BushlanovDev\MaxMessengerBot\Models\Recipient;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;


abstract class AbstractModel
{
    /**
     * @param array<string, mixed> $data
     *
     * @return static
     * @throws ReflectionException
     */
    public static function fromArray($data)
    {
        $reflectionClass = new ReflectionClass(static::class);
        $constructorArgs = [];

        foreach ((($nullsafeVariable1 = $reflectionClass->getConstructor()) ? $nullsafeVariable1->getParameters() : null) !== null ? ($nullsafeVariable1 = $reflectionClass->getConstructor()) ? $nullsafeVariable1->getParameters() : null : [] as $param) {
            $phpPropertyName = $param->getName();
            $property = $reflectionClass->getProperty($phpPropertyName);

            $jsonKey = self::toSnakeCase($phpPropertyName);
            $rawValue = isset($data[$jsonKey]) ? $data[$jsonKey] : null;

            if (!array_key_exists($jsonKey, $data) && $param->isDefaultValueAvailable()) {
                $constructorArgs[$phpPropertyName] = $param->getDefaultValue();
                continue;
            }

            $constructorArgs[$phpPropertyName] = self::castValue($rawValue, $property);
        }

//        if (!empty($constructorArgs['recipient'])) {
//            $constructorArgs['recipient'] = self::createRecipientClass($constructorArgs['recipient']);
//        }
//
//        if (!empty($constructorArgs['sender'])) {
//            $constructorArgs['sender'] = self::createUserClass($constructorArgs['sender']);
//        }
//
//        if (!empty($constructorArgs['message'])) {
//            $constructorArgs['message'] = self::createMessageClass($constructorArgs['message']);
//        }

        $constructorArgs = array_values($constructorArgs);//You need to force an array to be numerically indexed in order to unpack it. You do this using array_values:

        return new static(...$constructorArgs); // @phpstan-ignore-line
    }

    static public function createRecipientClass($data)
    {
        return new Recipient($data['chat_type'], $data['user_id'], $data['chat_id']);
    }

    static public function createUserClass($data)
    {
        return new User($data['user_id'],
            $data['first_name'],
            isset($data['last_name']) ? $data['last_name'] : null,
            isset($data['username']) ? $data['username'] : null,
            $data['is_bot'],
            $data['last_activity_time']);
    }

    static public function createMessageBodyClass($data)
    {
        return new MessageBody($data['mid'],
            $data['seq'],
            isset($data['text']) ? $data['text'] : null,
            isset($data['attachments']) ? $data['attachments'] : null,
            isset($data['markup']) ? $data['markup'] : null);
    }

    static public function createLinkedMessage($data)
    {
        return new LinkedMessage($data['type'],
            self::createMessageBodyClass($data['body']),
            self::createUserClass($data),
            isset($data['chat_id']) ? $data['chat_id'] : null
        );
    }

    static public function createMessageStat($data)
    {
        return new MessageStat($data['views']);
    }

    static public function createMessageClass($data)
    {
        return new Message($data['timestamp'],
            self::createRecipientClass($data['recipient']),
            self::createMessageBodyClass($data['body']),
            self::createUserClass($data['sender']),
            isset($data['url']) ? $data['url'] : null,
            isset($data['linked_message']) ? self::createLinkedMessage($data['linked_message']) : null,
            isset($data['message_stat']) ? self::createMessageStat($data['message_stat']) : null,
            isset($data['chat_id']) ? $data['chat_id'] : null,
            isset($data['recipient_id']) ? $data['recipient_id'] : null,
            isset($data['message_id']) ? $data['message_id'] : null
            );
    }

    /**
     * @return array<string, mixed>
     * @throws ReflectionException
     */
    public function toArray()
    {
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
        $result = [];

        foreach ($properties as $property) {

            $phpPropertyName = $property->getName();
            $value = $property->getValue($this);

            $jsonKey = self::toSnakeCase($phpPropertyName);

            $result[$jsonKey] = $this->convertValue($value);
        }

        return $result;
    }

    /**
     * @param string $input
     *
     * @return string
     */
    protected static function toSnakeCase($input)
    {
        return strtolower((string)preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     * @throws ReflectionException
     */
    protected function convertValue($value)
    {
        if ($value instanceof AbstractModel) {
            return $value->toArray();
        }

        if (is_array($value)) {
            return array_map([$this, 'convertValue'], $value);
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @param ReflectionProperty $property
     *
     * @return mixed
     * @throws ReflectionException
     */
    private static function castValue($value, ReflectionProperty $property)
    {
        try {
            if (is_null($value)) {
                return $value;
            }

            if (is_object($value)) {
                return $value;
            }

            $typeName = self::getTypeNameFromAnnotation($property);

            if (stripos($typeName, 'Abstract') !== false) {
                return $value;
            }

            switch ($typeName) {
                case 'int':
                    return (int)$value;
                case 'string':
                    return (string)$value;
                case 'bool':
                    return (bool)$value;
                case 'float':
                    return (float)$value;
                case 'array':
                    return self::castArray($value, $property);
            }

            $namespaces = ['BushlanovDev\MaxMessengerBot\Enums', 'BushlanovDev\MaxMessengerBot\Models', 'BushlanovDev\MaxMessengerBot\Models\Attachments', 'BushlanovDev\MaxMessengerBot\Models\Updates',
                'BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline', 'BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply', 'BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads',
                'BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Requests'];

            foreach ($namespaces as $namespace) {
                $className = $namespace . '\\' . $typeName;
                if (is_subclass_of($className, self::class) && is_array($value)) {
                    $value = $className::fromArray($value);
                    return $value;
                }
            }


        }
        catch (\Exception $e) {
            return $value;
        }

        return $value;

    }

    public static function getTypeNameFromAnnotation(ReflectionProperty $property)
    {
        if (preg_match('/@var\s+([^\s]+)/', $property->getDocComment(), $matches)) {
            $matches[1] = str_replace(['null', '[]'], '', $matches[1]);
            $matches[1] = trim($matches[1], '|');
            return $matches[1];
        }

        return null;
    }

    /**
     * @param mixed $value
     * @param ReflectionProperty $property
     *
     * @return array<string, mixed>
     * @throws ReflectionException
     */
    private static function castArray($value, ReflectionProperty $property)
    {
        if (!is_array($value)) {
            return (array)$value;
        }

        $attributes = method_exists($property, 'getAttributes') ? $property->getAttributes(ArrayOf::class) : [];

        if (empty($attributes)) {
            return $value;
        }

        /** @var ArrayOf $arrayOfAttribute */
        $arrayOfAttribute = $attributes[0]->newInstance();
        $itemClassName = $arrayOfAttribute->class;

        if (is_subclass_of($itemClassName, self::class)) {
            return array_map(
                function ($item) use ($itemClassName) {
                    return is_a($item, $itemClassName) ? $item : $itemClassName::fromArray($item);
                },
                $value
            );
        }

        return $value;
    }
}
