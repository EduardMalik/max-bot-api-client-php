<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Exceptions;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

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
    public function __construct(
        string $message,
        string $errorCode,
        ?ResponseInterface $response = null,
        ?int $httpStatusCode = null,
        ?Throwable $previous = null
    ) {
        $this->errorCode = $errorCode;
        $this->response = $response;
        $this->httpStatusCode = $httpStatusCode;
        parent::__construct($message, $httpStatusCode ?? 0, $previous);
    }

    public function getHttpStatusCode(): ?int
    {
        return $this->httpStatusCode;
    }
}
