<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Models;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use BushlanovDev\MaxMessengerBot\Models\BotCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

final class BotCommandTest extends TestCase
{
    /**
     * @return void
     */
    public function canBeCreatedFromArrayWithAllData()
    {
        $data = [
            'name' => 'start',
            'description' => 'Start the bot',
        ];
        $command = BotCommand::fromArray($data);
        $this->assertInstanceOf(BotCommand::class, $command);
        $this->assertSame('start', $command->name);
        $this->assertSame('Start the bot', $command->description);
        $arrayResult = $command->toArray();
        $this->assertIsArray($arrayResult);
        $this->assertSame($data, $arrayResult);
    }
    /**
     * @return void
     */
    public function canBeCreatedFromArrayWithOptionalDataNull()
    {
        $command = BotCommand::fromArray([
            'name' => 'help',
            'description' => null,
        ]);
        $this->assertInstanceOf(BotCommand::class, $command);
        $this->assertSame('help', $command->name);
        $this->assertNull($command->description);
    }
}
