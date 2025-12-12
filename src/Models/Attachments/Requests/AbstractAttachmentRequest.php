<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\AbstractAttachmentRequestPayload;

/**
 * Message attachments.
 */
abstract class AbstractAttachmentRequest extends AbstractModel
{
    /**
     * @readonly
     * @var \BushlanovDev\MaxMessengerBot\Enums\AttachmentType
     */
    public $type;
    /**
     * @readonly
     * @var \BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\AbstractAttachmentRequestPayload
     */
    public $payload;
    /**
     * @param mixed $type
     */
    public function __construct($type, AbstractAttachmentRequestPayload $payload)
    {
        $this->type = $type;
        $this->payload = $payload;
    }
}
