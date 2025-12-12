<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\MediaAttachmentPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\VideoThumbnail;

final class VideoAttachment extends AbstractAttachment
{
    /**
     * @var MediaAttachmentPayload
     * @readonly
     */
    public $payload;
    /**
     * @var VideoThumbnail|null
     * @readonly
     */
    public $thumbnail;
    /**
     * @var int|null
     * @readonly
     */
    public $width;
    /**
     * @var int|null
     * @readonly
     */
    public $height;
    /**
     * @var int|null
     * @readonly
     */
    public $duration;
    /**
     * @param MediaAttachmentPayload $payload Video attachment payload.
     * @param VideoThumbnail|null $thumbnail Video thumbnail.
     * @param int|null $width Video width.
     * @param int|null $height Video height.
     * @param int|null $duration Video duration in seconds.
     */
    public function __construct(
        MediaAttachmentPayload $payload,
        $thumbnail,
        $width,
        $height,
        $duration
    ) {
        $this->payload = $payload;
        $this->thumbnail = $thumbnail;
        $this->width = $width;
        $this->height = $height;
        $this->duration = $duration;
        parent::__construct(AttachmentType::Video);
    }
}
