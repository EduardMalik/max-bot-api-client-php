<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Requests;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\AbstractReplyButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\ReplyKeyboardAttachmentRequestPayload;

final class ReplyKeyboardAttachmentRequest extends AbstractAttachmentRequest
{
    /**
     * @param AbstractReplyButton[][] $buttons
     * @param bool $direct
     * @param int|null $directUserId
     */
    public function __construct(
        array $buttons,
        $direct = false,
        $directUserId = null
    ) {
        $direct = (bool) $direct;
        parent::__construct(
            AttachmentType::ReplyKeyboard,
            new ReplyKeyboardAttachmentRequestPayload($buttons, $direct, $directUserId)
        );
    }
}
