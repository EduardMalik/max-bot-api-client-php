<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;
use BushlanovDev\MaxMessengerBot\Enums\Intent;

/**
 * Sends a notification with payload to a bot (via WebHook or long polling).
 */
final class CallbackButton extends AbstractInlineButton
{
    /**
     * @readonly
     * @var string
     */
    public $payload;
    /**
     * @readonly
     * @var \BushlanovDev\MaxMessengerBot\Enums\Intent|null
     */
    public $intent;

    /**
     * @param string $text Visible button text (1 to 128 characters).
     * @param string $payload Button token (up to 1024 characters).
     * @param Intent|null $intent The intent of the button. Affects how it is displayed by the client.
     * @param ?\BushlanovDev\MaxMessengerBot\Enums\Intent::* $intent
     */
    public function __construct($text, $payload, $intent = null)
    {
        $text = (string) $text;
        $payload = (string) $payload;
        parent::__construct(InlineButtonType::Callback, $text);

        $this->payload = $payload;
        $this->intent = $intent;
    }
}
