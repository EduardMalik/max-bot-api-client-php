<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ContactAttachmentRequestPayload;

/**
 * Request to attach a contact card to a message.
 */
final class ContactAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param string|null $name Contact name.
     * @param int|null $contactId Contact identifier if it is a registered Max user.
     * @param string|null $vcfInfo Full information about the contact in VCF format.
     * @param string|null $vcfPhone Contact phone in VCF format.
     */
    public function __construct(
        $name = null,
        $contactId = null,
        $vcfInfo = null,
        $vcfPhone = null
    ) {
        parent::__construct(
            AttachmentType::Contact,
            new ContactAttachmentRequestPayload($name, $contactId, $vcfInfo, $vcfPhone)
        );
    }
}
