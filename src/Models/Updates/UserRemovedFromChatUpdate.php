<?php

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\User;

/**
 * You will receive this update when a user has been removed from a chat where the bot is an administrator.
 */
final class UserRemovedFromChatUpdate extends AbstractUpdate
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
    public $adminId;
    /**
     * @var bool
     * @readonly
     */
    public $isChannel;
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param int $chatId Chat identifier where the event has occurred.
     * @param User $user User who was removed from the chat.
     * @param int|null $adminId Administrator who removed the user from the chat.
     *                          Can be `null` if the user left the chat themselves.
     * @param bool $isChannel Indicates whether the user has been removed from a channel or not.
     */
    public function __construct(
        $timestamp,
        $chatId,
        User $user,
        $adminId,
        $isChannel
    ) {
        $timestamp = (int) $timestamp;
        $chatId = (int) $chatId;
        $isChannel = (bool) $isChannel;
        $this->chatId = $chatId;
        $this->user = $user;
        $this->adminId = $adminId;
        $this->isChannel = $isChannel;
        parent::__construct(UpdateType::UserRemoved, $timestamp);
    }
}
