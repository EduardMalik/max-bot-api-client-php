<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use InvalidArgumentException;

/**
 * Payload for a share (URL preview) attachment request.
 */
final class ShareAttachmentRequestPayload extends AbstractAttachmentRequestPayload
{
    /**
     * @var string|null
     * @readonly
     */
    public $url;
    /**
     * @var string|null
     * @readonly
     */
    public $token;
    /**
     * @param string|null $url URL to be attached to the message for media preview.
     * @param string|null $token Token of a previously generated share attachment.
     */
    public function __construct(
        $url = null,
        $token = null
    ) {
        $this->url = $url;
        $this->token = $token;
        if ($this->url === null && $this->token === null) {
            throw new InvalidArgumentException(
                'Provide one of "url" or "token" for ShareAttachmentRequestPayload.'
            );
        }
    }
}
