<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Updates\UserAddedToChatUpdate;
use BushlanovDev\MaxMessengerBot\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class UserAddedToChatUpdateTest extends TestCase
{
    /**
     * @return void
     */
    public function canBeCreatedWhenInvitedByUser()
    {
        $data = [
            'update_type' => 'user_added',
            'timestamp' => 1681000000,
            'chat_id' => 12345,
            'user' => [
                'user_id' => 101,
                'first_name' => 'New',
                'last_name' => 'Member',
                'is_bot' => false,
                'last_activity_time' => 1680000000,
                'username' => null,
            ],
            'inviter_id' => 202,
            'is_channel' => false,
        ];
        $update = UserAddedToChatUpdate::fromArray($data);
        $this->assertInstanceOf(UserAddedToChatUpdate::class, $update);
        $this->assertSame(UpdateType::UserAdded, $update->updateType);
        $this->assertSame(12345, $update->chatId);
        $this->assertSame(202, $update->inviterId);
        $this->assertFalse($update->isChannel);
        $this->assertInstanceOf(User::class, $update->user);
        $this->assertSame(101, $update->user->userId);
        $this->assertEquals($data, $update->toArray());
    }
    /**
     * @return void
     */
    public function canBeCreatedWhenJoinedByLink()
    {
        $data = [
            'update_type' => 'user_added',
            'timestamp' => 1681000001,
            'chat_id' => 54321,
            'user' => [
                'user_id' => 303,
                'first_name' => 'Link',
                'last_name' => 'Joiner',
                'is_bot' => false,
                'last_activity_time' => 1680000001,
                'username' => null,
            ],
            'inviter_id' => null,
            'is_channel' => true,
        ];
        $update = UserAddedToChatUpdate::fromArray($data);
        $this->assertInstanceOf(UserAddedToChatUpdate::class, $update);
        $this->assertNull($update->inviterId);
        $this->assertTrue($update->isChannel);
        $this->assertSame(303, $update->user->userId);
        $this->assertEquals($data, $update->toArray());
    }
}
