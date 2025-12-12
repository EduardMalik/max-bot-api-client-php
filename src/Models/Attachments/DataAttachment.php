<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;

/**
 * Represents an attachment containing a payload from a SendMessageButton.
 */
final class DataAttachment extends AbstractAttachment
{
    /**
     * @var string
     * @readonly
     */
    public $data;
    /**
     * @param string $data The payload from the button.
     */
    public function __construct($data)
    {
        $data = (string) $data;
        $this->data = $data;
        parent::__construct(AttachmentType::Data);
    }
}
