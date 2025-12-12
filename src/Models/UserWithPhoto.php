<?php

namespace BushlanovDev\MaxMessengerBot\Models;

final class UserWithPhoto extends AbstractModel
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
     * @param int $userId Users identifier.
     * @param string $firstName Users first name.
     * @param string|null $lastName Users last name.
     * @param string|null $username Unique public user name. Can be `null` if user is not accessible or it is not set.
     * @param bool $isBot `true` if user is bot.
     * @param int $lastActivityTime Time of last user activity in Max (Unix timestamp in milliseconds).
     * @param string|null $description UserWithPhoto description. Can be `null` if user did not fill it out.
     * @param string|null $avatarUrl URL of avatar.
     * @param string|null $fullAvatarUrl URL of avatar of a bigger size.
     */
    public function __construct($userId, $firstName, $lastName, $username, $isBot, $lastActivityTime, $description, $avatarUrl, $fullAvatarUrl)
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
        $this->description = $description;
        $this->avatarUrl = $avatarUrl;
        $this->fullAvatarUrl = $fullAvatarUrl;
    }
}
