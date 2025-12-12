<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Buttons\Reply;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendGeoLocationButton;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SendGeoLocationButtonTest extends TestCase
{
    /**
     * @return void
     */
    public function toArrayWithDefaultQuick()
    {
        $button = new SendGeoLocationButton('Share Location');
        $expected = [
            'type' => 'user_geo_location',
            'text' => 'Share Location',
            'quick' => false,
        ];
        $this->assertEquals($expected, $button->toArray());
    }
    /**
     * @return void
     */
    public function toArrayWithQuickTrue()
    {
        $button = new SendGeoLocationButton('Quick Share', true);
        $expected = [
            'type' => 'user_geo_location',
            'text' => 'Quick Share',
            'quick' => true,
        ];
        $this->assertEquals($expected, $button->toArray());
    }
}
