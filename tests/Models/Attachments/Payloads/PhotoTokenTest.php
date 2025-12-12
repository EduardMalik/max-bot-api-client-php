<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhotoTokenTest extends TestCase
{
    /**
     * @return void
     */
    public function itCorrectlyHandlesCreationSerializationAndState()
    {
        $tokenValue = 'some_unique_token_string_123';
        $rawData = ['token' => $tokenValue];
        $photoToken = PhotoToken::fromArray($rawData);
        $this->assertInstanceOf(PhotoToken::class, $photoToken);
        $this->assertSame($tokenValue, $photoToken->token);
        $serializedData = $photoToken->toArray();
        $this->assertEquals($rawData, $serializedData);
        $directInstance = new PhotoToken($tokenValue);
        $this->assertSame($tokenValue, $directInstance->token);
    }
}
