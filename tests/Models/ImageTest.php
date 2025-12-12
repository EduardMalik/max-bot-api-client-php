<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\Image;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ImageTest extends TestCase
{
    /**
     * @return void
     */
    public function canBeCreatedFromArray()
    {
        $data = [
            'url' => 'https://example.com/image.jpg',
        ];
        $image = Image::fromArray($data);
        $this->assertInstanceOf(Image::class, $image);
    }
}
