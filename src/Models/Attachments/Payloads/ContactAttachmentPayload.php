<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use BushlanovDev\MaxMessengerBot\Models\User;

/**
 * Payload of a contact attachment.
 */
final class ContactAttachmentPayload extends AbstractModel
{
    /**
     * @var string|null
     * @readonly
     */
    public $vcfInfo;
    /**
     * @var User|null
     * @readonly
     */
    public $maxInfo;
    /**
     * @param string|null $vcfInfo User info in VCF format.
     * @param User|null $maxInfo User info if the contact is a Max user.
     */
    public function __construct(?string $vcfInfo, ?User $maxInfo)
    {
        $this->vcfInfo = $vcfInfo;
        $this->maxInfo = $maxInfo;
    }
}
