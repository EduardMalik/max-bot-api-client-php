<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\Result;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ResultTest extends TestCase
{
    public function canBeCreatedFromArrayWithNullMessage(): void
    {
        $data = [
            'success' => true,
            'message' => null,
        ];
        $result = Result::fromArray($data);
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->success);
        $this->assertNull($result->message);
        $array = $result->toArray();
        $this->assertIsArray($array);
        $this->assertSame($data, $array);
    }
    public function canBeCreatedFromArray(): void
    {
        $data = [
            'success' => false,
            'message' => 'error message',
        ];
        $result = Result::fromArray($data);
        $this->assertInstanceOf(Result::class, $result);
        $this->assertFalse($result->success);
        $this->assertSame('error message', $result->message);
        $array = $result->toArray();
        $this->assertIsArray($array);
        $this->assertSame($data, $array);
    }
}
