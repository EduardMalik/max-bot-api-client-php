<?php

namespace BushlanovDev\MaxMessengerBot\Models\Markup;

use BushlanovDev\MaxMessengerBot\Enums\MarkupType;

/**
 * Represents a link in the text.
 */
final class LinkMarkup extends AbstractMarkup
{
    /**
     * @var string
     * @readonly
     */
    public $url;
    /**
     * @param int $from Element start index (zero-based) in text.
     * @param int $length Length of the markup element.
     * @param string $url Link's URL.
     */
    public function __construct(
        $from,
        $length,
        $url
    ) {
        $from = (int) $from;
        $length = (int) $length;
        $url = (string) $url;
        $this->url = $url;
        parent::__construct(MarkupType::Link, $from, $length);
    }
}
