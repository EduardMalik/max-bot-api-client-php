<?php

declare(strict_types=1);

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
    public function __construct(bool $success, ?string $message)
    {
        $this->success = $success;
        $this->message = $message;
    }
}
