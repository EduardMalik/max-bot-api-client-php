<?php

namespace BushlanovDev\MaxMessengerBot\Models\Markup;

use BushlanovDev\MaxMessengerBot\Enums\MarkupType;
use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Base class for a text markup element.
 */
abstract class AbstractMarkup extends AbstractModel
{
    /**
     * @var MarkupType
     * @readonly
     */
    public $type;
    /**
     * @var int
     * @readonly
     */
    public $from;
    /**
     * @var int
     * @readonly
     */
    public $length;
    /**
     * @param mixed $type The type of the markup element.
     * @param int $from Element start index (zero-based) in text.
     * @param int $length Length of the markup element.
     * @param \BushlanovDev\MaxMessengerBot\Enums\MarkupType::* $type
     */
    public function __construct($type, $from, $length)
    {
        $from = (int) $from;
        $length = (int) $length;
        $this->type = $type;
        $this->from = $from;
        $this->length = $length;
    }
}
