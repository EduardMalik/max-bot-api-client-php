<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\User;

/**
 * You will receive this update when a user has been added to a chat where the bot is an administrator.
 */
final class UserAddedToChatUpdate extends AbstractUpdate
{
    /**
     * @var int
     * @readonly
     */
    public $chatId;
    /**
     * @var User
     * @readonly
     */
    public $user;
    /**
     * @var int|null
     * @readonly
     */
    public $inviterId;
    /**
     * @var bool
     * @readonly
     */
    public $isChannel;
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param int $chatId Chat identifier where the event has occurred.
     * @param User $user User who was added to the chat.
     * @param int|null $inviterId User who added the new user to the chat.
     *                            Can be `null` if the user joined via a link.
     * @param bool $isChannel Indicates whether the user has been added to a channel or not.
     */
    public function __construct(
        int $timestamp,
        int $chatId,
        User $user,
        ?int $inviterId,
        bool $isChannel
    ) {
        $this->chatId = $chatId;
        $this->user = $user;
        $this->inviterId = $inviterId;
        $this->isChannel = $isChannel;
        parent::__construct(UpdateType::UserAdded, $timestamp);
    }
}
