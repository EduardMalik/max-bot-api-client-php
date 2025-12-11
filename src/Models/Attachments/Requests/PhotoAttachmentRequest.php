<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoToken;

/**
 * Request to attach some data to message.
 */
final class PhotoAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * Creates a request to attach an image by URL.
     *
     * @param string $url
     *
     * @return PhotoAttachmentRequest
     */
    public static function fromUrl($url): self
    {
        return new self(new PhotoAttachmentRequestPayload($url));
    }

    /**
     * Creates a request to attach an image using the token received after uploading.
     *
     * @param string $token
     *
     * @return PhotoAttachmentRequest
     */
    public static function fromToken($token): self
    {
        return new self(new PhotoAttachmentRequestPayload(null, $token));
    }

    /**
     * Creates a request to attach an image using the tokens received after uploading.
     *
     * @param PhotoToken[] $photos
     *
     * @return PhotoAttachmentRequest
     */
    public static function fromPhotos($photos): self
    {
        return new self(new PhotoAttachmentRequestPayload(null, null, $photos));
    }

    /**
     * @param PhotoAttachmentRequestPayload $payload Request to attach image.
     */
    function __construct(PhotoAttachmentRequestPayload $payload)
    {
        parent::__construct(AttachmentType::Image, $payload);
    }
}
