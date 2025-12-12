<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\ChatPatch;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class ChatPatchTest extends TestCase
{
    /**
     * @return void
     */
    public function toArrayIncludesOnlySetFields()
    {
        $patch = new ChatPatch();
        $this->assertEquals(['title' => 'New Title'], $patch->toArray());
    }
    /**
     * @return void
     */
    public function toArrayHandlesMultipleFields()
    {
        $photoPayload = new PhotoAttachmentRequestPayload(null, 'icon_token');
        $patch = new ChatPatch();
        $expected = [
            'title' => 'Updated Chat',
            'pin' => 'mid.12345',
            'icon' => [
                'url' => null,
                'token' => 'icon_token',
                'photos' => null,
            ],
        ];
        $this->assertEquals($expected, $patch->toArray());
    }
    /**
     * @return void
     */
    public function toArrayIsEmptyForEmptyPatch()
    {
        $patch = new ChatPatch();
        $this->assertEmpty($patch->toArray());
    }
}
