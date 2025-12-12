<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

/**
 * Payload for a sticker attachment request.
 */
final class StickerAttachmentRequestPayload extends AbstractAttachmentRequestPayload
{
    /**
     * @var string
     * @readonly
     */
    public $code;
    /**
     * @param string $code The unique code of the sticker to be sent.
     */
    public function __construct($code)
    {
        $code = (string) $code;
        $this->code = $code;
    }
}
