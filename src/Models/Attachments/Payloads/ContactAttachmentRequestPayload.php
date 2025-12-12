<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

/**
 * Payload for a contact attachment request.
 */
final class ContactAttachmentRequestPayload extends AbstractAttachmentRequestPayload
{
    /**
     * @var string|null
     * @readonly
     */
    public $name;
    /**
     * @var int|null
     * @readonly
     */
    public $contactId;
    /**
     * @var string|null
     * @readonly
     */
    public $vcfInfo;
    /**
     * @var string|null
     * @readonly
     */
    public $vcfPhone;
    /**
     * @param string|null $name Contact name.
     * @param int|null $contactId Contact identifier if it is a registered Max user.
     * @param string|null $vcfInfo Full information about the contact in VCF format.
     * @param string|null $vcfPhone Contact phone in VCF format.
     */
    public function __construct($name = null, $contactId = null, $vcfInfo = null, $vcfPhone = null)
    {
        $this->name = $name;
        $this->contactId = $contactId;
        $this->vcfInfo = $vcfInfo;
        $this->vcfPhone = $vcfPhone;
    }
}
