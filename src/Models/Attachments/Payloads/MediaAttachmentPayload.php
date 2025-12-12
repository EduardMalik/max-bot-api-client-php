<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Payload for media attachments like audio or video.
 */
final class MediaAttachmentPayload extends AbstractModel
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
     * @param string $url Media attachment URL.
     * @param string $token Token to reuse the same attachment in other messages.
     */
    public function __construct($url, $token)
    {
        $url = (string) $url;
        $token = (string) $token;
        $this->url = $url;
        $this->token = $token;
    }
}
