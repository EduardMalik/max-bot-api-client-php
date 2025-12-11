<?php

declare(strict_types=1);

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
    public function __construct(string $url, ?string $token = null)
    {
        $this->url = $url;
        $this->token = $token;
    }
}
