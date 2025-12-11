<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;

/**
 * You will get this update as soon as a message is removed.
 */
final class MessageRemovedUpdate extends AbstractUpdate
{
    /**
     * @var string
     * @readonly
     */
    public $messageId;
    /**
     * @var int
     * @readonly
     */
    public $chatId;
    /**
     * @var int
     * @readonly
     */
    public $userId;
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param string $messageId Identifier of the removed message.
     * @param int $chatId Chat identifier where the message has been deleted.
     * @param int $userId User who deleted this message.
     */
    public function __construct(
        int $timestamp,
        string $messageId,
        int $chatId,
        int $userId
    ) {
        $this->messageId = $messageId;
        $this->chatId = $chatId;
        $this->userId = $userId;
        parent::__construct(UpdateType::MessageRemoved, $timestamp);
    }
}
