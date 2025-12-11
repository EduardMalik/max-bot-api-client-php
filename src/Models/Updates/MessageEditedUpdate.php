<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Message;

/**
 * You will get this update as soon as a message is edited.
 */
final class MessageEditedUpdate extends AbstractUpdate
{
    /**
     * @var Message
     * @readonly
     */
    public $message;
    /**
     * @param int $timestamp Unix-time when the event has occurred.
     * @param Message $message The edited message.
     */
    public function __construct(
        int $timestamp,
        Message $message
    ) {
        $this->message = $message;
        parent::__construct(UpdateType::MessageEdited, $timestamp);
    }
}
