<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply;

use BushlanovDev\MaxMessengerBot\Enums\ReplyButtonType;

final class SendContactButton extends AbstractReplyButton
{
    /**
     * @param string $text Visible text of the button.
     */
    public function __construct($text)
    {
        $text = (string) $text;
        parent::__construct(ReplyButtonType::UserContact, $text);
    }
}
