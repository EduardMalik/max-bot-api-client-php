<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline;

use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;

/**
 * Button that creates a new chat associated with the message.
 * The bot will be added as an administrator by default.
 */
final class ChatButton extends AbstractInlineButton
{
    /**
     * @var string
     * @readonly
     */
    public $chatTitle;
    /**
     * @var string|null
     * @readonly
     */
    public $chatDescription;
    /**
     * @var string|null
     * @readonly
     */
    public $startPayload;
    /**
     * @var int|null
     * @readonly
     */
    public $uuid;
    /**
     * @param string $text Visible text of the button (1 to 128 characters).
     * @param string $chatTitle Title of the chat to be created (max 200 characters).
     * @param string|null $chatDescription Optional chat description (max 400 characters).
     * @param string|null $startPayload Optional payload that will be sent to the bot in a `message_chat_created` update.
     * @param int|null $uuid Optional unique identifier for the button. If not passed, it will be generated.
     */
    public function __construct(
        string $text,
        string $chatTitle,
        ?string $chatDescription = null,
        ?string $startPayload = null,
        ?int $uuid = null
    ) {
        $this->chatTitle = $chatTitle;
        $this->chatDescription = $chatDescription;
        $this->startPayload = $startPayload;
        $this->uuid = $uuid;
        parent::__construct(InlineButtonType::Chat, $text);
    }
}
