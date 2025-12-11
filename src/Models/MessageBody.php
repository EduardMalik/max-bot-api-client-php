<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Models\Attachments\AbstractAttachment;
use BushlanovDev\MaxMessengerBot\Models\Markup\AbstractMarkup;

/**
 * Body of created message. Text + attachments.
 */
final class MessageBody extends AbstractModel
{
    /**
     * @var string
     * @readonly
     */
    public $mid;
    /**
     * @var int
     * @readonly
     */
    public $seq;
    /**
     * @var string|null
     * @readonly
     */
    public $text;
    /**
     * @var AbstractAttachment[]|null
     * @readonly
     */
    public $attachments;
    /**
     * @var AbstractMarkup[]|null
     * @readonly
     */
    public $markup;
    /**
     * @param string $mid Unique identifier of message.
     * @param int $seq Sequence identifier of message in chat.
     * @param string|null $text Message text.
     * @param AbstractAttachment[]|null $attachments Message attachments.
     * @param AbstractMarkup[]|null $markup Message text markup.
     */
    public function __construct(string $mid, int $seq, ?string $text, ?array $attachments, ?array $markup)
    {
        $this->mid = $mid;
        $this->seq = $seq;
        $this->text = $text;
        $this->attachments = $attachments;
        $this->markup = $markup;
    }
}
