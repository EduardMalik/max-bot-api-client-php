<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Buttons\Reply;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendContactButton;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SendContactButtonTest extends TestCase
{
    /**
     * @return void
     */
    public function toArray()
    {
        $button = new SendContactButton('Share My Contact');
        $expected = [
            'type' => 'user_contact',
            'text' => 'Share My Contact',
        ];
        $this->assertEquals($expected, $button->toArray());
    }
}
