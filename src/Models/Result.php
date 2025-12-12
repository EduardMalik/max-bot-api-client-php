<?php

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Simple response to request.
 */
final class Result extends AbstractModel
{
    /**
     * @var bool
     * @readonly
     */
    public $success;
    /**
     * @var string|null
     * @readonly
     */
    public $message;
    /**
     * @param bool $success true if request was successful, false otherwise.
     * @param string|null $message Explanatory message if the result was not successful.
     */
    public function __construct($success, $message)
    {
        $success = (bool) $success;
        $this->success = $success;
        $this->message = $message;
    }
}
