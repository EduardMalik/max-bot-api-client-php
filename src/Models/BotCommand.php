<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Command supported by the bot.
 */
final class BotCommand extends AbstractModel
{
    /**
     * @var string
     * @readonly
     */
    public $name;
    /**
     * @var string|null
     * @readonly
     */
    public $description;
    /**
     * @param string $name Command name (1 to 64 characters)
     * @param string|null $description Command description (1 to 128 characters)
     */
    public function __construct(string $name, ?string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }
}
