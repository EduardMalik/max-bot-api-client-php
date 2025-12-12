<?php

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Callback;
use BushlanovDev\MaxMessengerBot\Models\Message;

/**
 * You will get this update as soon as a user presses a callback button.
 */
final class MessageCallbackUpdate extends AbstractUpdate
{
    /**
     * @var Callback
     * @readonly
     */
    public $callback;
    /**
     * @var Message|null
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
     * @param Callback $callback The callback query itself.
     * @param Message|null $message Original message with the inline keyboard. Can be null if the message was deleted.
     * @param string|null $userLocale Current user locale in IETF BCP 47 format.
     */
    public function __construct(
        $timestamp,
        Callback $callback,
        $message,
        $userLocale
    ) {
        $timestamp = (int) $timestamp;
        $this->callback = $callback;
        $this->message = $message;
        $this->userLocale = $userLocale;
        parent::__construct(UpdateType::MessageCallback, $timestamp);
    }
}
