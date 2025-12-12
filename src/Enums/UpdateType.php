<?php

namespace BushlanovDev\MaxMessengerBot\Enums;

class UpdateType
{
    const MessageCreated = 'message_created';
    const MessageCallback = 'message_callback';
    const MessageEdited = 'message_edited';
    const MessageRemoved = 'message_removed';
    const BotAdded = 'bot_added';
    const BotRemoved = 'bot_removed';
    const DialogMuted = 'dialog_muted';
    const DialogUnmuted = 'dialog_unmuted';
    const DialogCleared = 'dialog_cleared';
    const DialogRemoved = 'dialog_removed';
    const UserAdded = 'user_added';
    const UserRemoved = 'user_removed';
    const BotStarted = 'bot_started';
    const BotStopped = 'bot_stopped';
    const ChatTitleChanged = 'chat_title_changed';
    const MessageChatCreated = 'message_chat_created';

    /**
     * @param string $name
     */
    public static function fromName($name){

        return $name;
    }
}
