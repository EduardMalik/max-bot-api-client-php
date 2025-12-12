<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply;

use BushlanovDev\MaxMessengerBot\Enums\ReplyButtonType;
use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

abstract class AbstractReplyButton extends AbstractModel
{
    /**
     * @var ReplyButtonType
     * @readonly
     */
    public $type;
    /**
     * @var string
     * @readonly
     */
    public $text;
    /**
     * @param mixed $type The type of the reply button.
     * @param string $text Visible text of the button.
     * @param \BushlanovDev\MaxMessengerBot\Enums\ReplyButtonType::* $type
     */
    public function __construct($type, $text)
    {
        $text = (string) $text;
        $this->type = $type;
        $this->text = $text;
    }
}
