<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply;

use BushlanovDev\MaxMessengerBot\Enums\Intent;
use BushlanovDev\MaxMessengerBot\Enums\ReplyButtonType;

final class SendMessageButton extends AbstractReplyButton
{
    /**
     * @var string|null
     * @readonly
     */
    public $payload;
    /**
     * @var Intent
     * @readonly
     */
    public $intent = Intent::Default;
    /**
     * @param string $text Visible text of the button.
     * @param string|null $payload Button payload.
     * @param mixed $intent Intent of button.
     * @param \BushlanovDev\MaxMessengerBot\Enums\Intent::* $intent
     */
    public function __construct(
        string $text,
        ?string $payload = null,
        $intent = Intent::Default
    ) {
        $this->payload = $payload;
        $this->intent = $intent;
        parent::__construct(ReplyButtonType::Message, $text);
    }
}
