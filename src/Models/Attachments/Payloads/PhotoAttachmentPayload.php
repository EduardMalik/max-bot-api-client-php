<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Payload for a photo attachment.
 */
final class PhotoAttachmentPayload extends AbstractModel
{
    /**
     * @var string
     * @readonly
     */
    public $url;
    /**
     * @var string
     * @readonly
     */
    public $token;
    /**
     * @var int
     * @readonly
     */
    public $photoId;
    /**
     * @param string $url Media attachment URL.
     * @param string $token Token to reuse the same attachment in other messages.
     * @param int $photoId Unique identifier of this image.
     */
    public function __construct(string $url, string $token, int $photoId)
    {
        $this->url = $url;
        $this->token = $token;
        $this->photoId = $photoId;
    }
}
