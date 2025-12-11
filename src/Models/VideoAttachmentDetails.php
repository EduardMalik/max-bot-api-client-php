<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentRequestPayload;

/**
 * Contains detailed information about a video attachment.
 */
final class VideoAttachmentDetails extends AbstractModel
{
    /**
     * @var string
     * @readonly
     */
    public $token;
    /**
     * @var int
     * @readonly
     */
    public $width;
    /**
     * @var int
     * @readonly
     */
    public $height;
    /**
     * @var int
     * @readonly
     */
    public $duration;
    /**
     * @var VideoUrls|null
     * @readonly
     */
    public $urls;
    /**
     * @var PhotoAttachmentRequestPayload|null
     * @readonly
     */
    public $thumbnail;
    /**
     * @param string $token The video attachment token.
     * @param int $width The width of the video in pixels.
     * @param int $height The height of the video in pixels.
     * @param int $duration The duration of the video in seconds.
     * @param VideoUrls|null $urls URLs to download or play the video. Can be null if the video is unavailable.
     * @param PhotoAttachmentRequestPayload|null $thumbnail The video's thumbnail image information.
     */
    public function __construct(string $token, int $width, int $height, int $duration, ?VideoUrls $urls = null, ?PhotoAttachmentRequestPayload $thumbnail = null)
    {
        $this->token = $token;
        $this->width = $width;
        $this->height = $height;
        $this->duration = $duration;
        $this->urls = $urls;
        $this->thumbnail = $thumbnail;
    }
}
