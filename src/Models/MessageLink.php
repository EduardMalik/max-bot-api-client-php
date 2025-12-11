<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Enums\MessageLinkType;

/**
 * Message link.
 */
final class MessageLink extends AbstractModel
{
    /**
     * @var MessageLinkType
     * @readonly
     */
    public $type;
    /**
     * @var string
     * @readonly
     */
    public $mid;
    /**
     * @param mixed $type Type of message link.
     * @param string $mid Message identifier of original message.
     * @param \BushlanovDev\MaxMessengerBot\Enums\MessageLinkType::* $type
     */
    public function __construct($type, string $mid)
    {
        $this->type = $type;
        $this->mid = $mid;
    }
}
