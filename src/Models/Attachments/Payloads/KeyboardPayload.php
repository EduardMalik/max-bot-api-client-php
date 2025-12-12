<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\AbstractInlineButton;

/**
 * Represents an inline keyboard structure.
 */
final class KeyboardPayload extends AbstractModel
{
    /**
     * @var array
     * @readonly
     */
    public $buttons;
    /**
     * @param array $buttons Two-dimensional array of buttons.
     */
    public function __construct(array $buttons)
    {
        $this->buttons = $buttons;
    }
}
