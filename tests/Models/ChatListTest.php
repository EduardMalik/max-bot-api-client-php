<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Models\Chat;
use BushlanovDev\MaxMessengerBot\Models\ChatList;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class ChatListTest extends TestCase
{
    /**
     * @return void
     */
    public function canBeCreatedFromArray()
    {
        $rawData = [
            'chats' => [
                [
                    'chat_id' => 123,
                    'type' => 'chat',
                    'status' => 'active',
                    'last_event_time' => 1,
                    'participants_count' => 10,
                    'is_public' => false,
                ],
                [
                    'chat_id' => 456,
                    'type' => 'dialog',
                    'status' => 'active',
                    'last_event_time' => 2,
                    'participants_count' => 2,
                    'is_public' => false,
                ],
            ],
            'marker' => 98765,
        ];
        $chatList = ChatList::fromArray($rawData);
        $this->assertInstanceOf(ChatList::class, $chatList);
        $this->assertCount(2, $chatList->chats);
        $this->assertInstanceOf(Chat::class, $chatList->chats[0]);
        $this->assertSame(123, $chatList->chats[0]->chatId);
        $this->assertSame(98765, $chatList->marker);
    }
    /**
     * @return void
     */
    public function canBeCreatedWithEmptyChatsAndNullMarker()
    {
        $rawData = ['chats' => [], 'marker' => null];
        $chatList = ChatList::fromArray($rawData);
        $this->assertEmpty($chatList->chats);
        $this->assertNull($chatList->marker);
    }
}
