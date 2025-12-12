<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\AbstractInlineButton;

final class InlineKeyboardAttachmentRequestPayload extends AbstractAttachmentRequestPayload
{
    /**
     * @var AbstractInlineButton[][]
     * @readonly
     */
    public $buttons;
    /**
     * @param AbstractInlineButton[][] $buttons
     */
    public function __construct(array $buttons)
    {
        $this->buttons = $buttons;
    }
}
