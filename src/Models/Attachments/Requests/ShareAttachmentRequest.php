<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ShareAttachmentRequestPayload;

/**
 * Request to attach a media preview of an external URL.
 */
final class ShareAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * Creates a request to attach a URL preview.
     *
     * @param string $url The URL to generate a preview for.
     *
     * @return ShareAttachmentRequest
     */
    public static function fromUrl($url)
    {
        return new self(new ShareAttachmentRequestPayload($url));
    }

    /**
     * Creates a request to re-send a URL preview using its token.
     *
     * @param string $token The token of a previously generated preview.
     *
     * @return ShareAttachmentRequest
     */
    public static function fromToken($token)
    {
        return new self(new ShareAttachmentRequestPayload(null, $token));
    }

    function __construct(ShareAttachmentRequestPayload $payload)
    {
        parent::__construct(AttachmentType::Share, $payload);
    }
}
