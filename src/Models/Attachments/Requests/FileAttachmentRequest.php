<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\UploadedInfoAttachmentRequestPayload;

/**
 * Request to attach a generic file to a message.
 */
final class FileAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param string $token The unique token received after a successful file upload.
     */
    public function __construct($token)
    {
        $token = (string) $token;
        parent::__construct(
            AttachmentType::File,
            new UploadedInfoAttachmentRequestPayload($token)
        );
    }
}
