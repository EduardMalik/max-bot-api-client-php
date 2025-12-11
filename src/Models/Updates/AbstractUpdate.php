<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Updates;

use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * `Update` object represents different types of events that happened in chat.
 */
abstract class AbstractUpdate extends AbstractModel
{
    /**
     * @var UpdateType
     * @readonly
     */
    public $updateType;
    /**
     * @var int
     * @readonly
     */
    public $timestamp;
    /**
     * @param mixed $updateType Type of update.
     * @param int $timestamp Unix-time when event has occurred.
     * @param \BushlanovDev\MaxMessengerBot\Enums\UpdateType::* $updateType
     */
    public function __construct($updateType, int $timestamp)
    {
        $this->updateType = $updateType;
        $this->timestamp = $timestamp;
    }
}
