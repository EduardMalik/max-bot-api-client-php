<?php

namespace BushlanovDev\MaxMessengerBot\Exceptions;

class RateLimitExceededException extends ClientApiException
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
        parent::__construct($message, $errorCode, $response, 429, $previous);
    }
}
