<?php

declare(strict_types=1);

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
    public function __construct(string $url)
    {
        $this->url = $url;
    }
}
