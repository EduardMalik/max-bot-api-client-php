<?php

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\User;

/**
 * You will receive this update when the bot has been removed from a chat.
 */
final class BotRemovedFromChatUpdate extends AbstractUpdate
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
     * @var bool
     * @readonly
     */
    public $isChannel;
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param int $chatId Chat identifier the bot was removed from.
     * @param User $user User who removed the bot from the chat.
     * @param bool $isChannel Indicates whether the bot has been removed from a channel or not.
     */
    public function __construct(
        $timestamp,
        $chatId,
        User $user,
        $isChannel
    ) {
        $timestamp = (int) $timestamp;
        $chatId = (int) $chatId;
        $isChannel = (bool) $isChannel;
        $this->chatId = $chatId;
        $this->user = $user;
        $this->isChannel = $isChannel;
        parent::__construct(UpdateType::BotRemoved, $timestamp);
    }
}
