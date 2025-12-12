<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\UploadedInfoAttachmentRequestPayload;

/**
 * Request to attach a video to a message.
 */
final class VideoAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param string $token The unique token received after a successful video upload.
     */
    public function __construct($token)
    {
        $token = (string) $token;
        parent::__construct(
            AttachmentType::Video,
            new UploadedInfoAttachmentRequestPayload($token)
        );
    }
}
