<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Subscription;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class SubscriptionTest extends TestCase
{
    /**
     * @return void
     */
    public function canBeCreatedFromArrayWithAllData()
    {
        $data = [
            'url' => 'https://example.com/webhook',
            'time' => time(),
            'update_types' => [UpdateType::MessageCreated->value, UpdateType::BotStarted->value],
            'version' => '0.0.1',
        ];
        $subscription = Subscription::fromArray($data);
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertSame($data['url'], $subscription->url);
        $this->assertSame($data['time'], $subscription->time);
        $this->assertSame([UpdateType::MessageCreated, UpdateType::BotStarted], $subscription->updateTypes);
        $this->assertSame($data['version'], $subscription->version);
        $array = $subscription->toArray();
        $this->assertIsArray($array);
        $this->assertSame($data, $array);
    }
    /**
     * @return void
     */
    public function canBeCreatedFromArrayWithOptionalDataNull()
    {
        $data = [
            'url' => 'https://example.com/webhook',
            'time' => time(),
            'update_types' => null,
            'version' => null,
        ];
        $subscription = Subscription::fromArray($data);
        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertSame($data['url'], $subscription->url);
        $this->assertSame($data['time'], $subscription->time);
        $this->assertNull($subscription->updateTypes);
        $this->assertNull($subscription->version);
        $array = $subscription->toArray();
        $this->assertIsArray($array);
        $this->assertSame($data, $array);
    }
}
