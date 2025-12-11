<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentPayload;

final class PhotoAttachment extends AbstractAttachment
{
    /**
     * @var PhotoAttachmentPayload
     * @readonly
     */
    public $payload;
    /**
     * @param PhotoAttachmentPayload $payload Photo attachment payload.
     */
    public function __construct(PhotoAttachmentPayload $payload)
    {
        $this->payload = $payload;
        parent::__construct(AttachmentType::Image);
    }
}
