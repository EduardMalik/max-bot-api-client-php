<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentPayload;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class PhotoAttachmentPayloadTest extends TestCase
{
    /**
     * @return void
     */
    public function canBeCreatedAndSerialized()
    {
        $data = [
            'photo_id' => 987654321,
            'token' => 'some_received_photo_token',
            'url' => 'https://cdn.max.ru/photos/image.jpg'
        ];
        $payload = PhotoAttachmentPayload::fromArray($data);
        $this->assertInstanceOf(PhotoAttachmentPayload::class, $payload);
        $this->assertSame(987654321, $payload->photoId);
        $this->assertSame('some_received_photo_token', $payload->token);
        $this->assertSame('https://cdn.max.ru/photos/image.jpg', $payload->url);
        $this->assertEquals($data, $payload->toArray());
    }
}
