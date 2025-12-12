<?php

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Chat;

/**
 * Bot will get this update when a chat has been created as soon as
 * the first user clicked a `chat` button.
 */
final class MessageChatCreatedUpdate extends AbstractUpdate
{
    /**
     * @var Chat
     * @readonly
     */
    public $chat;
    /**
     * @var string
     * @readonly
     */
    public $messageId;
    /**
     * @var string|null
     * @readonly
     */
    public $startPayload;
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param Chat $chat The created chat.
     * @param string $messageId Message identifier where the button has been clicked.
     * @param string|null $startPayload Payload from the chat button.
     */
    public function __construct(
        $timestamp,
        Chat $chat,
        $messageId,
        $startPayload
    ) {
        $timestamp = (int) $timestamp;
        $messageId = (string) $messageId;
        $this->chat = $chat;
        $this->messageId = $messageId;
        $this->startPayload = $startPayload;
        parent::__construct(UpdateType::MessageChatCreated, $timestamp);
    }
}
