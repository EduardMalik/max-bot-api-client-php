<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\AbstractReplyButton;

final class ReplyKeyboardAttachmentRequestPayload extends AbstractAttachmentRequestPayload
{
    /**
     * @readonly
     */
    public $buttons;
    /**
     * @var bool
     * @readonly
     */
    public $direct = false;
    /**
     * @var int|null
     * @readonly
     */
    public $directUserId;
    /**
     * @param array $buttons Two-dimensional array of buttons.
     * @param bool $direct Applicable only for chats.
     * @param int|null $directUserId If set, reply keyboard will only be shown to this participant.
     */
    public function __construct(array $buttons, $direct = false, $directUserId = null)
    {
        $direct = (bool) $direct;
        $this->buttons = $buttons;
        $this->direct = $direct;
        $this->directUserId = $directUserId;
    }
}
