<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\UploadEndpoint;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class UploadEndpointTest extends TestCase
{
    /**
     * @return void
     */
    public function canBeCreatedFromArray()
    {
        $data = [
            'url' => 'https://example.com/upload',
            'token' => 'token',
        ];
        $uploadEndpoint = UploadEndpoint::fromArray($data);
        $this->assertInstanceOf(UploadEndpoint::class, $uploadEndpoint);
        $this->assertSame($data['url'], $uploadEndpoint->url);
        $this->assertSame($data['token'], $uploadEndpoint->token);
    }
    /**
     * @return void
     */
    public function canBeCreatedFromArrayWithoutToken()
    {
        $data = [
            'url' => 'https://example.com/upload',
        ];
        $uploadEndpoint = UploadEndpoint::fromArray($data);
        $this->assertInstanceOf(UploadEndpoint::class, $uploadEndpoint);
        $this->assertSame($data['url'], $uploadEndpoint->url);
        $this->assertNull($uploadEndpoint->token);
    }
}
