<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\ChatPatch;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class ChatPatchTest extends TestCase
{
    public function toArrayIncludesOnlySetFields(): void
    {
        $patch = new ChatPatch();
        $this->assertEquals(['title' => 'New Title'], $patch->toArray());
    }
    public function toArrayHandlesMultipleFields(): void
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
    public function toArrayIsEmptyForEmptyPatch(): void
    {
        $patch = new ChatPatch();
        $this->assertEmpty($patch->toArray());
    }
}
