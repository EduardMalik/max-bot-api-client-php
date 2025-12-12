<?php

namespace BushlanovDev\MaxMessengerBot\Exceptions;

class NotFoundException extends ClientApiException
{
    /**
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param \Exception|null $previous
     * @param string $message
     * @param string $errorCode
     */
    public function __construct(
        $message,
        $errorCode,
        $response,
        $previous = null
    ) {
        $message = (string) $message;
        $errorCode = (string) $errorCode;
        parent::__construct($message, $errorCode, $response, 404, $previous);
    }
}
