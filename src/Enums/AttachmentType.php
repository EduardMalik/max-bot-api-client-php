<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

class AttachmentType
{
    public const Image = 'image';
    public const Video = 'video';
    public const Audio = 'audio';
    public const File = 'file';
    public const Sticker = 'sticker';
    public const Contact = 'contact';
    public const InlineKeyboard = 'inline_keyboard';
    public const ReplyKeyboard = 'reply_keyboard';
    public const Location = 'location';
    public const Share = 'share';
    public const Data = 'data';

    public static function fromName(string $name){

        return $name;
    }
}
