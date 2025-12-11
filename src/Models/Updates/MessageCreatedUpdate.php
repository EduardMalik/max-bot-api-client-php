<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Message;

/**
 * You will get this `update` as soon as message is created.
 */
final class MessageCreatedUpdate extends AbstractUpdate
{
    /**
     * @var Message
     * @readonly
     */
    public $message;
    /**
     * @var string|null
     * @readonly
     */
    public $userLocale;
    /**
     * @param int $timestamp Unix-time when event has occurred.
     * @param Message $message Newly created message.
     * @param string|null $userLocale Current user locale in IETF BCP 47 format. Available only in dialogs.
     */
    public function __construct(
        int $timestamp,
        Message $message,
        ?string $userLocale
    ) {
        $this->message = $message;
        $this->userLocale = $userLocale;
        parent::__construct(UpdateType::MessageCreated, $timestamp);
    }
}
