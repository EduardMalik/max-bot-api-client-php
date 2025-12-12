<?php

namespace BushlanovDev\MaxMessengerBot\Enums;

class ReplyButtonType
{
    const Message = 'message';
    const UserGeoLocation = 'user_geo_location';
    const UserContact = 'user_contact';

    /**
     * @param string $name
     */
    public static function fromName($name){

        return $name;
    }
}
