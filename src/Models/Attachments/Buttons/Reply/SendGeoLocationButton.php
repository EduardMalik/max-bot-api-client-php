<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply;

use BushlanovDev\MaxMessengerBot\Enums\ReplyButtonType;

final class SendGeoLocationButton extends AbstractReplyButton
{
    /**
     * @var bool
     * @readonly
     */
    public $quick = false;
    /**
     * @param string $text Visible text of the button.
     * @param bool $quick If `true`, sends location without asking user's confirmation.
     */
    public function __construct(
        string $text,
        bool $quick = false
    ) {
        $this->quick = $quick;
        parent::__construct(ReplyButtonType::UserGeoLocation, $text);
    }
}
