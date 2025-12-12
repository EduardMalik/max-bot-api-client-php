<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;

/**
 * Opens the bot's mini-application.
 */
final class OpenAppButton extends AbstractInlineButton
{
    /**
     * @readonly
     * @var string|null
     */
    public $webApp;
    /**
     * @readonly
     * @var int|null
     */
    public $contactId;

    /**
     * @param string $text Visible button text (1 to 128 characters).
     * @param string|null $webApp The public name (username) of the bot or a link to it, whose mini-application should be launched.
     * @param int|null $contactId The ID of the bot whose mini-app should be launched.
     */
    public function __construct($text, $webApp = null, $contactId = null)
    {
        $text = (string) $text;
        parent::__construct(InlineButtonType::OpenApp, $text);

        $this->webApp = $webApp;
        $this->contactId = $contactId;
    }
}
