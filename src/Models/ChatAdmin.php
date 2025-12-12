<?php

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Enums\ChatAdminPermission;

/**
 * Represents an administrator to be set in a chat, linking a user ID with their permissions.
 */
final class ChatAdmin extends AbstractModel
{
    /**
     * @var int
     * @readonly
     */
    public $userId;
    /**
     * @var ChatAdminPermission[]
     * @readonly
     */
    public $permissions;
    /**
     * @param int $userId The identifier of the user to be made an admin.
     * @param ChatAdminPermission[] $permissions The list of permissions to grant to the user.
     */
    public function __construct(
        $userId,
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\BushlanovDev\MaxMessengerBot\Enums\ChatAdminPermission::class)]
        array $permissions
    )
    {
        $userId = (int) $userId;
        $this->userId = $userId;
        $this->permissions = $permissions;
    }
}
