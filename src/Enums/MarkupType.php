<?php

namespace BushlanovDev\MaxMessengerBot\Enums;

class MarkupType
{
    const Strong = 'strong';
    const Emphasized = 'emphasized';
    const Monospaced = 'monospaced';
    const Link = 'link';
    const Strikethrough = 'strikethrough';
    const Underline = 'underline';
    const UserMention = 'user_mention';
    const Heading = 'heading';
    const Highlighted = 'highlighted';

    /**
     * @param string $name
     */
    public static function fromName($name){

        return $name;
    }
}
