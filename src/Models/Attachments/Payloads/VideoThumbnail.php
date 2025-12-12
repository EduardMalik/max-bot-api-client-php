<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Represents a video thumbnail image.
 */
final class VideoThumbnail extends AbstractModel
{
    /**
     * @var string
     * @readonly
     */
    public $url;
    /**
     * @param string $url Media attachment URL.
     */
    public function __construct($url)
    {
        $url = (string) $url;
        $this->url = $url;
    }
}
