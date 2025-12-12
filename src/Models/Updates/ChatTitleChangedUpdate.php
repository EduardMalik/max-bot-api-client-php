<?php

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\User;

/**
 * Bot gets this type of update as soon as the title has been changed in a chat.
 */
final class ChatTitleChangedUpdate extends AbstractUpdate
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
     * @var string
     * @readonly
     */
    public $title;
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param int $chatId Chat identifier where the event has occurred.
     * @param User $user User who changed the title.
     * @param string $title The new title.
     */
    public function __construct(
        $timestamp,
        $chatId,
        User $user,
        $title
    ) {
        $timestamp = (int) $timestamp;
        $chatId = (int) $chatId;
        $title = (string) $title;
        $this->chatId = $chatId;
        $this->user = $user;
        $this->title = $title;
        parent::__construct(UpdateType::ChatTitleChanged, $timestamp);
    }
}
