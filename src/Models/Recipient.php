<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Enums\ChatType;

/**
 * Message recipient. Could be user or chat.
 */
final class Recipient extends AbstractModel
{
    /**
     * @var ChatType
     * @readonly
     */
    public $chatType;
    /**
     * @var int|null
     * @readonly
     */
    public $userId;
    /**
     * @var int|null
     * @readonly
     */
    public $chatId;
    /**
     * @param mixed $chatType Chat type (dialog, chat or channel).
     * @param int|null $userId User identifier, if message was sent to user.
     * @param int|null $chatId Chat identifier.
     * @param \BushlanovDev\MaxMessengerBot\Enums\ChatType::* $chatType
     */
    public function __construct($chatType, ?int $userId, ?int $chatId)
    {
        $this->chatType = $chatType;
        $this->userId = $userId;
        $this->chatId = $chatId;
    }
}
