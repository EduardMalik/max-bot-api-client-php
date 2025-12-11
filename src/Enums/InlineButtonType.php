<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

class InlineButtonType
{
    public const Callback = 'callback';
    public const Link = 'link';
    public const RequestGeoLocation = 'request_geo_location';
    public const RequestContact = 'request_contact';
    public const OpenApp = 'open_app';
    public const Message = 'message';
    public const Chat = 'chat';

    public static function fromName(string $name){

        return $name;
    }
}
