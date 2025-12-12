<?php

namespace BushlanovDev\MaxMessengerBot\Models;

final class Image extends AbstractModel
{
    /**
     * @var string
     * @readonly
     */
    public $url;
    /**
     * @param string $url URL of image.
     */
    public function __construct($url)
    {
        $url = (string) $url;
        $this->url = $url;
    }
}
