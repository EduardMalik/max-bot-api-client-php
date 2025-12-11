<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ContactAttachmentPayload;

final class ContactAttachment extends AbstractAttachment
{
    /**
     * @var ContactAttachmentPayload
     * @readonly
     */
    public $payload;
    /**
     * @param ContactAttachmentPayload $payload Contact attachment payload.
     */
    public function __construct(ContactAttachmentPayload $payload)
    {
        $this->payload = $payload;
        parent::__construct(AttachmentType::Contact);
    }
}
