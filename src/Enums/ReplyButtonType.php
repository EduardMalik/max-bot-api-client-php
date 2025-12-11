<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Enums;

class ReplyButtonType
{
    public const Message = 'message';
    public const UserGeoLocation = 'user_geo_location';
    public const UserContact = 'user_contact';

    public static function fromName(string $name){

        return $name;
    }
}
