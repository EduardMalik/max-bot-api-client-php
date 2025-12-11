<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\KeyboardPayload;

final class InlineKeyboardAttachment extends AbstractAttachment
{
    /**
     * @var KeyboardPayload
     * @readonly
     */
    public $payload;
    /**
     * @param KeyboardPayload $payload Keyboard payload.
     */
    public function __construct(KeyboardPayload $payload)
    {
        $this->payload = $payload;
        parent::__construct(AttachmentType::InlineKeyboard);
    }
}
