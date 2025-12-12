<?php

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Endpoint you should upload to your binaries
 */
final class UploadEndpoint extends AbstractModel
{
    /**
     * @var string
     * @readonly
     */
    public $url;
    /**
     * @var string|null
     * @readonly
     */
    public $token;
    /**
     * @param string $url URL to upload.
     * @param string|null $token Video or audio token for send message.
     */
    public function __construct($url, $token = null)
    {
        $url = (string) $url;
        $this->url = $url;
        $this->token = $token;
    }
}
