<?php

namespace BushlanovDev\MaxMessengerBot\Models\Markup;

use BushlanovDev\MaxMessengerBot\Enums\MarkupType;

/**
 * Represents a **strong** (bold) part of the text.
 */
final class StrongMarkup extends AbstractMarkup
{
    /**
     * @param int $from Element start index (zero-based) in text.
     * @param int $length Length of the markup element.
     */
    public function __construct(
        $from,
        $length
    ) {
        $from = (int) $from;
        $length = (int) $length;
        parent::__construct(MarkupType::Strong, $from, $length);
    }
}
