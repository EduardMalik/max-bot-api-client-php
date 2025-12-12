<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ShareAttachmentRequestPayload;

/**
 * Represents a share (URL preview) attachment.
 */
final class ShareAttachment extends AbstractAttachment
{
    /**
     * @readonly
     * @var \BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ShareAttachmentRequestPayload
     */
    public $payload;
    /**
     * @readonly
     * @var string|null
     */
    public $title;
    /**
     * @readonly
     * @var string|null
     */
    public $description;
    /**
     * @readonly
     * @var string|null
     */
    public $imageUrl;
    /**
     * @param string|null $title
     * @param string|null $description
     * @param string|null $imageUrl
     */
    public function __construct(
        ShareAttachmentRequestPayload $payload,
        $title,
        $description,
        $imageUrl
    ) {
        $this->payload = $payload;
        $this->title = $title;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        parent::__construct(AttachmentType::Share);
    }
}
