<?php

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\User;

/**
 * Event of enabling notifications in a dialog.
 */
final class DialogUnmutedUpdate extends AbstractUpdate
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
     * @var string|null
     * @readonly
     */
    public $userLocale;
    /**
     * @param int $timestamp Unix-time when event has occurred.
     * @param int $chatId Dialog identifier where event has occurred.
     * @param User $user User pressed the 'Start' button.
     * @param string|null $userLocale Current user locale in IETF BCP 47 format.
     */
    public function __construct(
        $timestamp,
        $chatId,
        User $user,
        $userLocale
    ) {
        $timestamp = (int) $timestamp;
        $chatId = (int) $chatId;
        $this->chatId = $chatId;
        $this->user = $user;
        $this->userLocale = $userLocale;
        parent::__construct(UpdateType::DialogUnmuted, $timestamp);
    }
}
