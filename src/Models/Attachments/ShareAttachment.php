<?php

declare(strict_types=1);

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
    public function __construct(
        ShareAttachmentRequestPayload $payload,
        ?string $title,
        ?string $description,
        ?string $imageUrl
    ) {
        $this->payload = $payload;
        $this->title = $title;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        parent::__construct(AttachmentType::Share);
    }
}
