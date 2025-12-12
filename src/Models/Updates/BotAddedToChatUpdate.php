<?php

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\User;

/**
 * You will receive this update when the bot has been added to a chat.
 */
final class BotAddedToChatUpdate extends AbstractUpdate
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
     * @param int $chatId Chat ID where the bot was added.
     * @param User $user User who added the bot to the chat.
     * @param bool $isChannel Indicates whether the bot has been added to a channel or not.
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
        parent::__construct(UpdateType::BotAdded, $timestamp);
    }
}
