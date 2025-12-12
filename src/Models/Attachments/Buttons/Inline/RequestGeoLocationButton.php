<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;

/**
 * After pressing this type of button client sends new message with attachment of current user geo location.
 */
final class RequestGeoLocationButton extends AbstractInlineButton
{
    /**
     * @readonly
     * @var bool
     */
    public $quick;

    /**
     * @param string $text Visible button text (1 to 128 characters).
     * @param bool $quick If true, sends location without asking user's confirmation.
     */
    public function __construct($text, $quick = false)
    {
        $text = (string) $text;
        $quick = (bool) $quick;
        parent::__construct(InlineButtonType::RequestGeoLocation, $text);

        $this->quick = $quick;
    }
}
