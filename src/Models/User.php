<?php

namespace BushlanovDev\MaxMessengerBot\Models;

final class User extends AbstractModel
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
     * @param int $userId Users identifier.
     * @param string $firstName Users first name.
     * @param string|null $lastName Users last name.
     * @param string|null $username Unique public user name. Can be `null` if user is not accessible or it is not set.
     * @param bool $isBot Is the user a bot.
     * @param int $lastActivityTime Time of last user activity in Max (Unix timestamp in milliseconds).
     *                              Can be outdated if user disabled its "online" status in settings.
     */
    public function __construct($userId, $firstName, $lastName, $username, $isBot, $lastActivityTime)
    {
        $userId = (int) $userId;
        $firstName = (string) $firstName;
        $isBot = (bool) $isBot;
        $lastActivityTime = (int) $lastActivityTime;
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->isBot = $isBot;
        $this->lastActivityTime = $lastActivityTime;
    }
}
