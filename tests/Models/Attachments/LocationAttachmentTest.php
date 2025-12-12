<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Models\Attachments\LocationAttachment;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class LocationAttachmentTest extends TestCase
{
    /**
     * @return void
     */
    public function canBeCreatedFromArray()
    {
        $data = ['type' => 'location', 'latitude' => 55.75, 'longitude' => 37.61];
        $attachment = LocationAttachment::fromArray($data);
        $this->assertInstanceOf(LocationAttachment::class, $attachment);
        $this->assertSame(55.75, $attachment->latitude);
    }
}
