<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\StickerAttachmentPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StickerAttachmentPayloadTest extends TestCase
{
    /**
     * @return void
     */
    public function canBeCreatedAndSerialized()
    {
        $payload = new StickerAttachmentPayload('https://example.com/sticker.webp', 'sticker_code_abc');
        $this->assertSame('https://example.com/sticker.webp', $payload->url);
        $this->assertSame('sticker_code_abc', $payload->code);
        $this->assertEquals(
            ['url' => 'https://example.com/sticker.webp', 'code' => 'sticker_code_abc'],
            $payload->toArray()
        );
    }
}
