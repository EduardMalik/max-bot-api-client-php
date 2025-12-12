<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;
use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

abstract class AbstractInlineButton extends AbstractModel
{
    /**
     * @var InlineButtonType
     * @readonly
     */
    public $type;
    /**
     * @var string
     * @readonly
     */
    public $text;
    /**
     * @param mixed $type The type of the inline button.
     * @param string $text Visible text of the button.
     * @param \BushlanovDev\MaxMessengerBot\Enums\InlineButtonType::* $type
     */
    public function __construct($type, $text)
    {
        $text = (string) $text;
        $this->type = $type;
        $this->text = $text;
    }
}
