<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;

/**
 * Information about the current bot.
 */
final class BotInfo extends AbstractModel
{
    /**
     * @var int
     * @readonly
     */
    public $userId;
    /**
     * @var string
     * @readonly
     */
    public $firstName;
    /**
     * @var string|null
     * @readonly
     */
    public $lastName;
    /**
     * @var string|null
     * @readonly
     */
    public $username;
    /**
     * @var bool
     * @readonly
     */
    public $isBot;
    /**
     * @var int
     * @readonly
     */
    public $lastActivityTime;
    /**
     * @var string|null
     * @readonly
     */
    public $description;
    /**
     * @var string|null
     * @readonly
     */
    public $avatarUrl;
    /**
     * @var string|null
     * @readonly
     */
    public $fullAvatarUrl;
    /**
     * @var BotCommand[]|null
     * @readonly
     */
    public $commands;
    /**
     * @param int $userId ID user.
     * @param string $firstName User display name.
     * @param string|null $lastName User's display last name.
     * @param string|null $username Unique public name of the user, may be null if the user is not available or no name is set.
     * @param bool $isBot Is the user a bot.
     * @param int $lastActivityTime User last activity time in MAX (Unix time in milliseconds). May be irrelevant if the user has disabled the "online" status in the settings.
     * @param string|null $description User description, may be null if the user has not filled it in (up to 16000 characters).
     * @param string|null $avatarUrl Avatar URL.
     * @param string|null $fullAvatarUrl Larger Avatar URL.
     * @param BotCommand[]|null $commands Commands supported by the bot (up to 32 elements).
     */
    public function __construct(
        int $userId,
        string $firstName,
        ?string $lastName,
        ?string $username,
        bool $isBot,
        int $lastActivityTime,
        ?string $description,
        ?string $avatarUrl,
        ?string $fullAvatarUrl,
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\BushlanovDev\MaxMessengerBot\Models\BotCommand::class)]
        ?array $commands
    )
    {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->isBot = $isBot;
        $this->lastActivityTime = $lastActivityTime;
        $this->description = $description;
        $this->avatarUrl = $avatarUrl;
        $this->fullAvatarUrl = $fullAvatarUrl;
        $this->commands = $commands;
    }
}
