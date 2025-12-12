<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Enums\ChatAdminPermission;
use BushlanovDev\MaxMessengerBot\Models\ChatAdmin;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class ChatAdminTest extends TestCase
{
    /**
     * @return void
     */
    public function toArraySerializesCorrectly()
    {
        $admin = new ChatAdmin(123, [ChatAdminPermission::Write, ChatAdminPermission::PinMessage]);
        $expected = [
            'user_id' => 123,
            'permissions' => ['write', 'pin_message'],
        ];
        $this->assertEquals($expected, $admin->toArray());
    }
}
