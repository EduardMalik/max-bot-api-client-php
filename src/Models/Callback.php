<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Object sent to bot when user presses a callback button.
 */
final class Callback extends AbstractModel
{
    /**
     * @var int
     * @readonly
     */
    public $timestamp;
    /**
     * @var string
     * @readonly
     */
    public $callbackId;
    /**
     * @var string
     * @readonly
     */
    public $payload;
    /**
     * @var User
     * @readonly
     */
    public $user;
    /**
     * @param int $timestamp Unix-time when user pressed the button.
     * @param string $callbackId Identifier of the callback, unique for the message.
     * @param string $payload Payload from the pressed button.
     * @param User $user User who pressed the button.
     */
    public function __construct(int $timestamp, string $callbackId, string $payload, User $user)
    {
        $this->timestamp = $timestamp;
        $this->callbackId = $callbackId;
        $this->payload = $payload;
        $this->user = $user;
    }
}
