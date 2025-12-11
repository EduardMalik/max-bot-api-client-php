<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;

/**
 * Represents a paginated list of chat members.
 */
final class ChatMembersList extends AbstractModel
{
    /**
     * @var ChatMember[]
     * @readonly
     */
    public $members;
    /**
     * @var int|null
     * @readonly
     */
    public $marker;
    /**
     * @param ChatMember[] $members A list of chat members.
     * @param int|null $marker A pointer to the next page of data. Null if this is the last page.
     */
    public function __construct(
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\BushlanovDev\MaxMessengerBot\Models\ChatMember::class)]
        array $members,
        ?int $marker
    )
    {
        $this->members = $members;
        $this->marker = $marker;
    }
}
