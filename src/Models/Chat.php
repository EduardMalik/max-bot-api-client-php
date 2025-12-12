<?php

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Enums\ChatStatus;
use BushlanovDev\MaxMessengerBot\Enums\ChatType;

/**
 * Information about the chat.
 */
final class Chat extends AbstractModel
{
    /**
     * @var int
     * @readonly
     */
    public $chatId;
    /**
     * @var ChatType
     * @readonly
     */
    public $type;
    /**
     * @var ChatStatus
     * @readonly
     */
    public $status;
    /**
     * @var int
     * @readonly
     */
    public $lastEventTime;
    /**
     * @var int
     * @readonly
     */
    public $participantsCount;
    /**
     * @var bool
     * @readonly
     */
    public $isPublic;
    /**
     * @var string|null
     * @readonly
     */
    public $title;
    /**
     * @var Image|null
     * @readonly
     */
    public $icon;
    /**
     * @var int|null
     * @readonly
     */
    public $ownerId;
    /**
     * @var string|null
     * @readonly
     */
    public $link;
    /**
     * @var string|null
     * @readonly
     */
    public $description;
    /**
     * @var UserWithPhoto|null
     * @readonly
     */
    public $dialogWithUser;
    /**
     * @var int|null
     * @readonly
     */
    public $messagesCount;
    /**
     * @var string|null
     * @readonly
     */
    public $chatMessageId;
    /**
     * @var Message|null
     * @readonly
     */
    public $pinnedMessage;
    /**
     * @param int $chatId Chats identifier.
     * @param mixed $type Type of chat. One of: `dialog`, `chat`, `channel`.
     * @param mixed $status Chat status.
     * @param int $lastEventTime Time of last event occurred in chat.
     * @param int $participantsCount Number of people in chat. Always 2 for `dialog` chat type.
     * @param bool $isPublic Is current chat publicly available. Always false for dialogs.
     * @param string|null $title Visible title of chat. Can be null for dialogs.
     * @param Image|null $icon Icon of chat.
     * @param int|null $ownerId Identifier of chat owner. Visible only for chat admins
     * @param string|null $link Link on chat.
     * @param string|null $description Chat description.
     * @param UserWithPhoto|null $dialogWithUser Another user in conversation. For `dialog` type chats only.
     * @param int|null $messagesCount Messages count in chat. Only for group chats and channels. Not available for dialogs.
     * @param string|null $chatMessageId Identifier of message that contains `chat` button initialized chat.
     * @param Message|null $pinnedMessage Pinned message in chat or channel. Returned only when single chat is requested.
     * @param \BushlanovDev\MaxMessengerBot\Enums\ChatType::* $type
     * @param \BushlanovDev\MaxMessengerBot\Enums\ChatStatus::* $status
     */
    public function __construct($chatId, $type, $status, $lastEventTime, $participantsCount, $isPublic, $title, $icon, $ownerId, $link, $description, $dialogWithUser, $messagesCount, $chatMessageId, $pinnedMessage)
    {
        $chatId = (int) $chatId;
        $lastEventTime = (int) $lastEventTime;
        $participantsCount = (int) $participantsCount;
        $isPublic = (bool) $isPublic;
        $this->chatId = $chatId;
        $this->type = $type;
        $this->status = $status;
        $this->lastEventTime = $lastEventTime;
        $this->participantsCount = $participantsCount;
        $this->isPublic = $isPublic;
        $this->title = $title;
        $this->icon = $icon;
        $this->ownerId = $ownerId;
        $this->link = $link;
        $this->description = $description;
        $this->dialogWithUser = $dialogWithUser;
        $this->messagesCount = $messagesCount;
        $this->chatMessageId = $chatMessageId;
        $this->pinnedMessage = $pinnedMessage;
    }
}
