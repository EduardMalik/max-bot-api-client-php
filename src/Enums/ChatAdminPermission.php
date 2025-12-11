<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

class ChatAdminPermission
{
    public const ReadAllMessages = 'read_all_messages';
    public const AddRemoveMembers = 'add_remove_members';
    public const AddAdmins = 'add_admins';
    public const ChangeChatInfo = 'change_chat_info';
    public const PinMessage = 'pin_message';
    public const Write = 'write';
}
