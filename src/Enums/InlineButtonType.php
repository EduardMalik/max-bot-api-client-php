<?php

namespace BushlanovDev\MaxMessengerBot\Enums;

class InlineButtonType
{
    const Callback = 'callback';
    const Link = 'link';
    const RequestGeoLocation = 'request_geo_location';
    const RequestContact = 'request_contact';
    const OpenApp = 'open_app';
    const Message = 'message';
    const Chat = 'chat';

    /**
     * @param string $name
     */
    public static function fromName($name){

        return $name;
    }
}
