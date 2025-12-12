<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\AbstractAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\DataAttachment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class DataAttachmentTest extends TestCase
{
    /**
     * @return void
     */
    public function canBeCreatedAndSerialized()
    {
        $attachment = new DataAttachment('some_payload_from_button');
        $this->assertSame(AttachmentType::Data, $attachment->type);
        $this->assertSame('some_payload_from_button', $attachment->data);
        $expectedArray = [
            'type' => 'data',
            'data' => 'some_payload_from_button',
        ];
        $this->assertEquals($expectedArray, $attachment->toArray());
    }
    /**
     * @return void
     */
    public function canBeCreatedFromArray()
    {
        $data = [
            'type' => 'data',
            'data' => 'payload123',
        ];
        $attachment = DataAttachment::fromArray($data);
        $this->assertInstanceOf(DataAttachment::class, $attachment);
        $this->assertSame(AttachmentType::Data, $attachment->type);
        $this->assertSame('payload123', $attachment->data);
    }
}
