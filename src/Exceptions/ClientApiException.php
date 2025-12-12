<?php

namespace BushlanovDev\MaxMessengerBot\Exceptions;

use RuntimeException;

class ClientApiException extends RuntimeException
{
    /**
     * @readonly
     * @var string
     */
    public $errorCode;
    /**
     * @readonly
     * @var \Psr\Http\Message\ResponseInterface|null
     */
    public $response;
    /**
     * @readonly
     * @var int|null
     */
    public $httpStatusCode;
    /**
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param int|null $httpStatusCode
     * @param \Exception|null $previous
     * @param string $message
     * @param string $errorCode
     */
    public function __construct(
        $message,
        $errorCode,
        $response = null,
        $httpStatusCode = null,
        $previous = null
    ) {
        $message = (string) $message;
        $errorCode = (string) $errorCode;
        $this->errorCode = $errorCode;
        $this->response = $response;
        $this->httpStatusCode = $httpStatusCode;
        parent::__construct($message, isset($httpStatusCode) ? $httpStatusCode : 0, $previous);
    }

    /**
     * @return int|null
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}
