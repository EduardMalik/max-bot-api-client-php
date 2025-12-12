<?php

namespace BushlanovDev\MaxMessengerBot\Enums;

class AttachmentType
{
    const Image = 'image';
    const Video = 'video';
    const Audio = 'audio';
    const File = 'file';
    const Sticker = 'sticker';
    const Contact = 'contact';
    const InlineKeyboard = 'inline_keyboard';
    const ReplyKeyboard = 'reply_keyboard';
    const Location = 'location';
    const Share = 'share';
    const Data = 'data';

    /**
     * @param string $name
     */
    public static function fromName($name){

        return $name;
    }
}
