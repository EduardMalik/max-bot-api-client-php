<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;

final class LocationAttachment extends AbstractAttachment
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
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(
        $latitude,
        $longitude
    ) {
        $latitude = (double) $latitude;
        $longitude = (double) $longitude;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        parent::__construct(AttachmentType::Location);
    }
}
