<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;

/**
 * Represents a paginated list of chats.
 */
final class ChatList extends AbstractModel
{
    /**
     * @var Chat[]
     * @readonly
     */
    public $chats;
    /**
     * @var int|null
     * @readonly
     */
    public $marker;
    /**
     * @param Chat[] $chats List of requested chats.
     * @param int|null $marker Reference to the next page of requested chats. Can be null if it's the last page.
     */
    public function __construct(
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\BushlanovDev\MaxMessengerBot\Models\Chat::class)]
        array $chats,
        ?int $marker
    )
    {
        $this->chats = $chats;
        $this->marker = $marker;
    }
}
