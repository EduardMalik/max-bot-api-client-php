<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Encoded information of uploaded image
 */
final class PhotoToken extends AbstractModel
{
    /**
     * @var string
     * @readonly
     */
    public $token;
    /**
     * @param string $token Encoded information of uploaded image.
     */
    public function __construct($token)
    {
        $token = (string) $token;
        $this->token = $token;
    }
}
