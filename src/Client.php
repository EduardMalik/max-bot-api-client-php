<?php

namespace BushlanovDev\MaxMessengerBot;

use BushlanovDev\MaxMessengerBot\Exceptions\AttachmentNotReadyException;
use BushlanovDev\MaxMessengerBot\Exceptions\ForbiddenException;
use BushlanovDev\MaxMessengerBot\Exceptions\ClientApiException;
use BushlanovDev\MaxMessengerBot\Exceptions\MethodNotAllowedException;
use BushlanovDev\MaxMessengerBot\Exceptions\NetworkException;
use BushlanovDev\MaxMessengerBot\Exceptions\NotFoundException;
use BushlanovDev\MaxMessengerBot\Exceptions\RateLimitExceededException;
use BushlanovDev\MaxMessengerBot\Exceptions\SerializationException;
use BushlanovDev\MaxMessengerBot\Exceptions\UnauthorizedException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;

/**
 * The low-level HTTP client responsible for communicating with the Max Bot API.
 * It handles request signing, error handling, and JSON serialization/deserialization.
 * This class is an abstraction over any PSR-18 compatible HTTP client.
 */
final class Client implements ClientApiInterface
{
    /**
     * @var string
     * @readonly
     */
    private $accessToken;
    /**
     * @var ClientInterface
     * @readonly
     */
    private $httpClient;
    /**
     * @var RequestFactoryInterface
     * @readonly
     */
    private $requestFactory;
    /**
     * @var StreamFactoryInterface
     * @readonly
     */
    private $streamFactory;
    /**
     * @var string
     * @readonly
     */
    private $baseUrl;
    /**
     * @var string|null
     * @readonly
     */
    private $apiVersion;
    /**
     * @var LoggerInterface
     * @readonly
     */
    private $logger;
    /**
     * @param string $accessToken Your bot's access token from @MasterBot.
     * @param \GuzzleHttp\Client $httpClient A PSR-18 compatible HTTP client (e.g., Guzzle).
     * @param RequestFactoryInterface $requestFactory A PSR-17 factory for creating requests.
     * @param StreamFactoryInterface $streamFactory A PSR-17 factory for creating request body streams.
     * @param string $baseUrl The base URL for API requests.
     * @param string|null $apiVersion The API version to use for requests.
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        $accessToken,
        $httpClient,
        $requestFactory,
        $streamFactory,
        $baseUrl,
        $apiVersion = null,
        $logger = null
    ) {
        $accessToken = (string) $accessToken;
        $baseUrl = (string) $baseUrl;
        $logger = isset($logger) ? $logger : new NullLogger();
        $this->accessToken = $accessToken;
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->baseUrl = $baseUrl;
        $this->apiVersion = $apiVersion;
        $this->logger = $logger;
        if (empty($accessToken)) {
            throw new InvalidArgumentException('Access token cannot be empty.');
        }
    }

    /**
     * @inheritDoc
     * @param string $method
     * @param string $uri
     * @param mixed[] $queryParams
     * @param mixed[] $body
     * @return mixed[]
     */
    public function request($method, $uri, $queryParams = [], $body = [])
    {
        if (!empty($this->apiVersion)) {
            $queryParams['v'] = $this->apiVersion;
        }

        $this->logger->debug('Sending API request', [
            'method' => $method,
            'url' => $this->baseUrl . $uri,
            'body' => $body,
        ]);

        $fullUrl = $this->baseUrl . $uri . '?' . http_build_query($queryParams);
        $request = $this->requestFactory
            ->createRequest($method, $fullUrl)
            ->withHeader('Authorization', $this->accessToken);

        if (!empty($body)) {
            $payload = json_encode($body, 0);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new SerializationException('Failed to encode request body to JSON:' . $body, 0);
            }

            $stream = $this->streamFactory->createStream($payload);
            $request = $request
                ->withBody($stream)
                ->withHeader('Content-Type', 'application/json; charset=utf-8');
        }

        try {
            $response = $this->httpClient->send($request);
        } catch (\Exception $e) {
            // This catches network errors, DNS failures, timeouts, etc.
            $this->logger->error('Network exception during API request', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            throw new NetworkException($e->getMessage(), $e->getCode(), $e);
        }

        $this->handleErrorResponse($response);

        $responseBody = (string)$response->getBody();

        $this->logger->debug('Received API response', [
            'status' => $response->getStatusCode(),
            'body' => $responseBody,
        ]);

        // Handle successful but empty responses (e.g., from DELETE endpoints)
        if (empty($responseBody)) {
            // The API spec often returns {"success": true}, so we can simulate that
            // for consistency if the body is truly empty.
            return ['success' => true];
        }

        $decode = json_decode($responseBody, true, 512, 0);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new SerializationException('Failed to decode API response JSON:' . $responseBody, 0);
        }

        return $decode;
    }

    /**
     * @inheritDoc
     * @param string $uri
     * @param mixed $fileContents
     * @param string $fileName
     * @return string
     */
    public function multipartUpload($uri, $fileContents, $fileName)
    {
        $boundary = '--------------------------' . microtime(true);
        $bodyStream = $this->streamFactory->createStream();

        $bodyStream->write("--$boundary\r\n");
        $bodyStream->write("Content-Disposition: form-data; name=\"data\"; filename=\"{$fileName}\"\r\n");
        $bodyStream->write("Content-Type: application/octet-stream\r\n\r\n");

        if (is_resource($fileContents)) {
            $bodyStream->write((string)stream_get_contents($fileContents));
        } else {
            $bodyStream->write((string)$fileContents);
        }
        $bodyStream->write("\r\n");
        $bodyStream->write("--$boundary--\r\n");

        $request = $this->requestFactory
            ->createRequest('POST', $uri)
            ->withHeader('Content-Type', 'multipart/form-data; boundary=' . $boundary)
            ->withHeader('Authorization', $this->accessToken)
            ->withBody($bodyStream);

        try {
            $response = $this->httpClient->send($request);
        } catch (\Exception $e) {
            throw new NetworkException($e->getMessage(), $e->getCode(), $e);
        }

        $this->handleErrorResponse($response);

        return (string)$response->getBody();
    }

    /**
     * @inheritDoc
     * @param string $uploadUrl
     * @param mixed $fileResource
     * @param string $fileName
     * @param int $fileSize
     * @param int $chunkSize
     * @return string
     */
    public function resumableUpload(
        $uploadUrl,
        $fileResource,
        $fileName,
        $fileSize,
        $chunkSize = 1048576
    ) {
        if (!is_resource($fileResource) || get_resource_type($fileResource) !== 'stream') {
            throw new InvalidArgumentException('fileResource must be a valid stream resource.');
        }

        // @phpstan-ignore-next-line
        if ($fileSize <= 0) {
            throw new InvalidArgumentException('File size must be greater than 0.');
        }

        $startByte = 0;
        $finalResponseBody = '';

        while (!feof($fileResource)) {
            $chunk = fread($fileResource, $chunkSize);
            if ($chunk === false) {
                // @codeCoverageIgnoreStart
                throw new RuntimeException('Failed to read chunk from file stream.');
                // @codeCoverageIgnoreEnd
            }

            $chunkLength = strlen($chunk);
            if ($chunkLength === 0) {
                break;
            }

            $endByte = $startByte + $chunkLength - 1;

            $chunkStream = $this->streamFactory->createStream($chunk);
            $request = $this->requestFactory->createRequest('POST', $uploadUrl)
                ->withBody($chunkStream)
                ->withHeader('Content-Type', 'application/octet-stream')
                ->withHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->withHeader('Content-Range', "bytes {$startByte}-{$endByte}/{$fileSize}")
                ->withHeader('Authorization', $this->accessToken);

            try {
                $response = $this->httpClient->send($request);
            } catch (\Exception $e) {
                throw new NetworkException($e->getMessage(), $e->getCode(), $e);
            }

            $this->handleErrorResponse($response);

            // The final response might contain the retval
            $finalResponseBody = (string)$response->getBody();

            $startByte += $chunkLength;
        }

        // According to docs, for video/audio the token is sent separately,
        // and the upload response contains 'retval'. We return the body of the last response.
        return $finalResponseBody;
    }

    /**
     * Checks the response for an error status code and throws a corresponding typed exception.
     *
     * @throws ClientApiException
     * @return void
     */
    private function handleErrorResponse($response)
    {
        $statusCode = $response->getStatusCode();

        // 2xx codes are considered successful.
        if ($statusCode >= 200 && $statusCode < 300) {
            return;
        }

        $responseBody = (string)$response->getBody();
        $data = json_decode($responseBody, true) !== null ? json_decode($responseBody, true) : [];
        $errorCode = isset($data['code']) ? $data['code'] : 'unknown';
        $errorMessage = isset($data['message']) ? $data['message'] : 'An unknown error occurred.';

        $this->logger->error('API error response received', [
            'status' => $statusCode,
            'body' => $responseBody,
        ]);

        switch ($statusCode) {
            case 401:
            case 403:
            case 404:
            case 405:
            case 429:
            default:
        }
    }

    /**
     * @param string $message
     * @param string $errorCode
     * @param ResponseInterface $response
     * @param int|null $httpStatusCode
     *
     * @return ClientApiException
     */
    private function mapErrorCodeToException(
        $message,
        $errorCode,
        $response,
        $httpStatusCode = null
    ) {
        $message = (string) $message;
        $errorCode = (string) $errorCode;
        switch ($errorCode) {
            case 'attachment.not.ready':
                return new AttachmentNotReadyException($message, $errorCode, $response, $httpStatusCode);
            default:
                return new ClientApiException($message, $errorCode, $response, $httpStatusCode);
        }
    }
}
