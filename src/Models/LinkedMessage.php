<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Enums\MessageLinkType;

/**
 * Represents a forwarded or replied message linked to the main message.
 */
final class LinkedMessage extends AbstractModel
{
    /**
     * @var MessageLinkType
     * @readonly
     */
    public $type;
    /**
     * @var MessageBody
     * @readonly
     */
    public $message;
    /**
     * @var User|null
     * @readonly
     */
    public $sender;
    /**
     * @var int|null
     * @readonly
     */
    public $chatId;
    /**
     * @param mixed $type Type of linked message (forward or reply).
     * @param MessageBody $message The body of the original message.
     * @param User|null $sender The sender of the original message. Can be null if posted on behalf of a channel.
     * @param int|null $chatId The chat where the message was originally posted (for forwarded messages).
     * @param \BushlanovDev\MaxMessengerBot\Enums\MessageLinkType::* $type
     */
    public function __construct($type, MessageBody $message, ?User $sender, ?int $chatId)
    {
        $this->type = $type;
        $this->message = $message;
        $this->sender = $sender;
        $this->chatId = $chatId;
    }
}
