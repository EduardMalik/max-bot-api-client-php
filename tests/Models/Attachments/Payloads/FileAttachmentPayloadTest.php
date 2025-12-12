<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\FileAttachmentPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FileAttachmentPayloadTest extends TestCase
{
    /**
     * @return void
     */
    public function canBeCreatedAndSerialized()
    {
        $payload = new FileAttachmentPayload('https://example.com/doc.pdf', 'file_token_456');
        $this->assertSame('https://example.com/doc.pdf', $payload->url);
        $this->assertSame('file_token_456', $payload->token);
        $this->assertEquals(['url' => 'https://example.com/doc.pdf', 'token' => 'file_token_456'], $payload->toArray());
    }
}
