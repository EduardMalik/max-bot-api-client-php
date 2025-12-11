<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\StickerAttachmentPayload;

final class StickerAttachment extends AbstractAttachment
{
    /**
     * @var StickerAttachmentPayload
     * @readonly
     */
    public $payload;
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
     * @param StickerAttachmentPayload $payload Sticker attachment payload.
     * @param int $width
     * @param int $height
     */
    public function __construct(
        StickerAttachmentPayload $payload,
        int $width,
        int $height
    ) {
        $this->payload = $payload;
        $this->width = $width;
        $this->height = $height;
        parent::__construct(AttachmentType::Sticker);
    }
}
