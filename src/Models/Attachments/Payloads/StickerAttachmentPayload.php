<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Payload for a sticker attachment.
 */
final class StickerAttachmentPayload extends AbstractModel
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
    public $code;
    /**
     * @param string $url Media attachment URL.
     * @param string $code Sticker identifier.
     */
    public function __construct($url, $code)
    {
        $url = (string) $url;
        $code = (string) $code;
        $this->url = $url;
        $this->code = $code;
    }
}
