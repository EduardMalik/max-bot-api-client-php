<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Markup;

use BushlanovDev\MaxMessengerBot\Enums\MarkupType;

/**
 * Represents a user mention in the text.
 */
final class UserMentionMarkup extends AbstractMarkup
{
    /**
     * @var string|null
     * @readonly
     */
    public $userLink;
    /**
     * @var int|null
     * @readonly
     */
    public $userId;
    /**
     * @param int $from Element start index (zero-based) in text.
     * @param int $length Length of the markup element.
     * @param string|null $userLink "@username" of the mentioned user.
     * @param int|null $userId Identifier of the mentioned user without a username.
     */
    public function __construct(
        int $from,
        int $length,
        ?string $userLink,
        ?int $userId
    ) {
        $this->userLink = $userLink;
        $this->userId = $userId;
        parent::__construct(MarkupType::UserMention, $from, $length);
    }
}
