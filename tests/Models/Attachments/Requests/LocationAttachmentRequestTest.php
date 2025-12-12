<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\LocationAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\LocationAttachmentRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class LocationAttachmentRequestTest extends TestCase
{
    /**
     * @return void
     */
    public function itCreatesRequestWithCoordinatesAndSerializes()
    {
        $latitude = 55.7558;
        $longitude = 37.6173;
        $request = new LocationAttachmentRequest($latitude, $longitude);
        $this->assertInstanceOf(LocationAttachmentRequest::class, $request);
        $this->assertSame(AttachmentType::Location, $request->type);
        $this->assertInstanceOf(LocationAttachmentRequestPayload::class, $request->payload);
        $this->assertSame($latitude, $request->payload->latitude);
        $this->assertSame($longitude, $request->payload->longitude);
        $expectedArray = [
            'type' => 'location',
            'payload' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
        ];
        $this->assertEquals($expectedArray, $request->toArray());
    }
}
