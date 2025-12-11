<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

class SenderAction
{
    public const TypingOn = 'typing_on';
    public const SendingPhoto = 'sending_photo';
    public const SendingVideo = 'sending_video';
    public const SendingAudio = 'sending_audio';
    public const SendingFile = 'sending_file';
    public const MarkSeen = 'mark_seen';
}
