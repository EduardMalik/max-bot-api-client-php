<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\AbstractReplyButton;

final class ReplyKeyboardAttachment extends AbstractAttachment
{
    /**
     * @readonly
     */
    public $buttons;
    /**
     * @param array $buttons
     */
    public function __construct(array $buttons)
    {
        $this->buttons = $buttons;
        parent::__construct(AttachmentType::ReplyKeyboard);
    }
}
