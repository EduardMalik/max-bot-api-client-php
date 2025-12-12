<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Buttons\Reply;

use BushlanovDev\MaxMessengerBot\Enums\Intent;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendMessageButton;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SendMessageButtonTest extends TestCase
{
    /**
     * @return void
     */
    public function toArrayWithDefaults()
    {
        $button = new SendMessageButton('Click Me');
        $expected = [
            'type' => 'message',
            'text' => 'Click Me',
            'payload' => null,
            'intent' => 'default',
        ];
        $this->assertEquals($expected, $button->toArray());
    }
    /**
     * @return void
     */
    public function toArrayWithAllParameters()
    {
        $button = new SendMessageButton('Confirm', 'confirm-action-123', Intent::Positive);
        $expected = [
            'type' => 'message',
            'text' => 'Confirm',
            'payload' => 'confirm-action-123',
            'intent' => 'positive',
        ];
        $this->assertEquals($expected, $button->toArray());
    }
}
