<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\ModelFactory;
use BushlanovDev\MaxMessengerBot\Models\Message;
use BushlanovDev\MaxMessengerBot\Models\MessageBody;
use BushlanovDev\MaxMessengerBot\Models\Recipient;
use BushlanovDev\MaxMessengerBot\Models\UpdateList;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageCreatedUpdate;
use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class UpdateListTest extends TestCase
{
    /**
     * @return void
     */
    public function factoryCorrectlyCreatesUpdateList()
    {
        $data = [
            'updates' => [
                [
                    'update_type' => UpdateType::MessageCreated->value,
                    'timestamp' => 1678886400000,
                    'message' => [
                        'timestamp' => 1678886400000,
                        'body' => ['mid' => 'mid.123', 'seq' => 1, 'text' => 'Hello'],
                        'recipient' => ['chat_type' => 'dialog', 'user_id' => 123],
                    ],
                    'user_locale' => 'ru-RU',
                ],
            ],
            'marker' => 1,
        ];
        $factory = new ModelFactory();
        $updateList = $factory->createUpdateList($data);
        $this->assertInstanceOf(UpdateList::class, $updateList);
        $this->assertCount(1, $updateList->updates);
        $this->assertInstanceOf(MessageCreatedUpdate::class, $updateList->updates[0]);
        $this->assertSame(1, $updateList->marker);
    }
    /**
     * @return void
     */
    public function directCallToFromArrayThrowsException()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessageMatches('/Cannot create .* directly from an array/');
        UpdateList::fromArray(['updates' => [], 'marker' => 1]);
    }
}
