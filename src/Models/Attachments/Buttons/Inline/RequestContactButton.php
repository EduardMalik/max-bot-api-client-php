<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;

/**
 * Requests the user permission to access contact information (phone number, short link, email).
 */
final class RequestContactButton extends AbstractInlineButton
{
    /**
     * @param string $text Visible button text (1 to 128 characters).
     */
    public function __construct($text)
    {
        $text = (string) $text;
        parent::__construct(InlineButtonType::RequestContact, $text);
    }
}
