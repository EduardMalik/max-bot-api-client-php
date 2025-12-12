<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoAttachmentRequestPayload;
use BushlanovDev\MaxMessengerBot\Models\BotPatch;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class BotPatchTest extends TestCase
{
    /**
     * @return void
     */
    public function toArrayIncludesOnlyExplicitlySetFields()
    {
        $patch = new BotPatch();
        $this->assertEquals(['name' => 'New Name'], $patch->toArray());
    }
    /**
     * @return void
     */
    public function toArrayIncludesFieldsSetToNull()
    {
        $patch = new BotPatch();
        $this->assertEquals(['description' => null], $patch->toArray());
    }
    /**
     * @return void
     */
    public function toArrayHandlesMultipleSetFields()
    {
        $photoPayload = new PhotoAttachmentRequestPayload(null, 'photo123');
        $patch = new BotPatch();
        $expected = [
            'name' => 'Updated Bot',
            'description' => null,
            'photo' => [
                'url' => null,
                'token' => 'photo123',
                'photos' => null,
            ],
        ];
        $this->assertEquals($expected, $patch->toArray());
    }
    /**
     * @return void
     */
    public function toArrayIsEmptyWhenNoArgumentsPassed()
    {
        $patch = new BotPatch();
        $this->assertEmpty($patch->toArray());
    }
}
