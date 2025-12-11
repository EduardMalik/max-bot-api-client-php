<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

/**
 * Payload for attachments that are uploaded to the server first (video, audio, file).
 */
final class UploadedInfoAttachmentRequestPayload extends AbstractAttachmentRequestPayload
{
    /**
     * @var string
     * @readonly
     */
    public $token;
    /**
     * @param string $token The unique token received after a successful file upload.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
