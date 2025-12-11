<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

/**
 * Payload for a location attachment request.
 */
final class LocationAttachmentRequestPayload extends AbstractAttachmentRequestPayload
{
    /**
     * @var float
     * @readonly
     */
    public $latitude;
    /**
     * @var float
     * @readonly
     */
    public $longitude;
    /**
     * @param float $latitude Latitude as a floating-point number.
     * @param float $longitude Longitude as a floating-point number.
     */
    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}
