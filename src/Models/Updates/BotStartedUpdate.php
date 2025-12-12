<?php

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\User;

/**
 * Bot gets this type of update as soon as user pressed `Start` button.
 */
final class BotStartedUpdate extends AbstractUpdate
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
    public $payload;
    /**
     * @var string|null
     * @readonly
     */
    public $userLocale;
    /**
     * @param int $timestamp Unix-time when event has occurred.
     * @param int $chatId Dialog identifier where event has occurred.
     * @param User $user User pressed the 'Start' button.
     * @param string|null $payload Additional data from deep-link passed on bot startup.
     * @param string|null $userLocale Current user locale in IETF BCP 47 format.
     */
    public function __construct(
        $timestamp,
        $chatId,
        User $user,
        $payload,
        $userLocale
    ) {
        $timestamp = (int) $timestamp;
        $chatId = (int) $chatId;
        $this->chatId = $chatId;
        $this->user = $user;
        $this->payload = $payload;
        $this->userLocale = $userLocale;
        parent::__construct(UpdateType::BotStarted, $timestamp);
    }
}
