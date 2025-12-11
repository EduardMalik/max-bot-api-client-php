<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Enums\ChatAdminPermission;

/**
 * Represents a member of a chat, including their user information and chat-specific status.
 */
final class ChatMember extends AbstractModel
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
     * @var int
     * @readonly
     */
    public $lastAccessTime;
    /**
     * @var bool
     * @readonly
     */
    public $isOwner;
    /**
     * @var bool
     * @readonly
     */
    public $isAdmin;
    /**
     * @var int
     * @readonly
     */
    public $joinTime;
    /**
     * @var ChatAdminPermission[]|null
     * @readonly
     */
    public $permissions;
    /**
     * @param int $userId User's identifier.
     * @param string $firstName User's first name.
     * @param string|null $lastName User's last name.
     * @param string|null $username User's public username.
     * @param bool $isBot True if the user is a bot.
     * @param int $lastActivityTime Time of the user's last activity in Max.
     * @param string|null $description User's profile description.
     * @param string|null $avatarUrl URL of the user's avatar.
     * @param string|null $fullAvatarUrl URL of the user's full-sized avatar.
     * @param int $lastAccessTime The time the user last accessed the chat.
     * @param bool $isOwner True if this member is the owner of the chat.
     * @param bool $isAdmin True if this member is an administrator of the chat.
     * @param int $joinTime The time the user joined the chat.
     * @param ChatAdminPermission[]|null $permissions A list of permissions if the member is an admin, otherwise null.
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
        int $lastAccessTime,
        bool $isOwner,
        bool $isAdmin,
        int $joinTime,
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\BushlanovDev\MaxMessengerBot\Enums\ChatAdminPermission::class)]
        ?array $permissions
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
        $this->lastAccessTime = $lastAccessTime;
        $this->isOwner = $isOwner;
        $this->isAdmin = $isAdmin;
        $this->joinTime = $joinTime;
        $this->permissions = $permissions;
    }
}
