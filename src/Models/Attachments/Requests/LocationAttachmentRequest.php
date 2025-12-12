<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\LocationAttachmentRequestPayload;

/**
 * Request to attach a geographical location to a message.
 */
final class LocationAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param float $latitude Latitude as a floating-point number.
     * @param float $longitude Longitude as a floating-point number.
     */
    public function __construct($latitude, $longitude)
    {
        $latitude = (double) $latitude;
        $longitude = (double) $longitude;
        parent::__construct(
            AttachmentType::Location,
            new LocationAttachmentRequestPayload($latitude, $longitude)
        );
    }
}
