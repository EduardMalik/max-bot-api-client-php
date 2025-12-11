<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

class MarkupType
{
    public const Strong = 'strong';
    public const Emphasized = 'emphasized';
    public const Monospaced = 'monospaced';
    public const Link = 'link';
    public const Strikethrough = 'strikethrough';
    public const Underline = 'underline';
    public const UserMention = 'user_mention';
    public const Heading = 'heading';
    public const Highlighted = 'highlighted';

    public static function fromName(string $name){

        return $name;
    }
}
