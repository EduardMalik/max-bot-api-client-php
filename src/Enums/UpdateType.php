<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

class UpdateType
{
    public const MessageCreated = 'message_created';
    public const MessageCallback = 'message_callback';
    public const MessageEdited = 'message_edited';
    public const MessageRemoved = 'message_removed';
    public const BotAdded = 'bot_added';
    public const BotRemoved = 'bot_removed';
    public const DialogMuted = 'dialog_muted';
    public const DialogUnmuted = 'dialog_unmuted';
    public const DialogCleared = 'dialog_cleared';
    public const DialogRemoved = 'dialog_removed';
    public const UserAdded = 'user_added';
    public const UserRemoved = 'user_removed';
    public const BotStarted = 'bot_started';
    public const BotStopped = 'bot_stopped';
    public const ChatTitleChanged = 'chat_title_changed';
    public const MessageChatCreated = 'message_chat_created';

    public static function fromName(string $name){

        return $name;
    }
}
