<?php

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\User;

/**
 * Event when a user mutes a conversation with a bot.
 */
final class DialogMutedUpdate extends AbstractUpdate
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
     * @var int|null
     * @readonly
     */
    public $mutedUntil;
    /**
     * @var string|null
     * @readonly
     */
    public $userLocale;
    /**
     * @param int $timestamp Unix-time when event has occurred.
     * @param int $chatId Dialog identifier where event has occurred.
     * @param User $user User pressed the 'Start' button.
     * @param int|null $mutedUntil The time in Unix format before which the dialog was disabled.
     * @param string|null $userLocale Current user locale in IETF BCP 47 format.
     */
    public function __construct(
        $timestamp,
        $chatId,
        User $user,
        $mutedUntil,
        $userLocale
    ) {
        $timestamp = (int) $timestamp;
        $chatId = (int) $chatId;
        $this->chatId = $chatId;
        $this->user = $user;
        $this->mutedUntil = $mutedUntil;
        $this->userLocale = $userLocale;
        parent::__construct(UpdateType::DialogMuted, $timestamp);
    }
}
