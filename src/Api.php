<?php

namespace BushlanovDev\MaxMessengerBot;

use BushlanovDev\MaxMessengerBot\Enums\MessageFormat;
use BushlanovDev\MaxMessengerBot\Enums\SenderAction;
use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Enums\UploadType;
use BushlanovDev\MaxMessengerBot\Exceptions\ClientApiException;
use BushlanovDev\MaxMessengerBot\Exceptions\NetworkException;
use BushlanovDev\MaxMessengerBot\Exceptions\SerializationException;
use BushlanovDev\MaxMessengerBot\Models\AbstractModel;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\AbstractAttachmentRequest;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\AudioAttachmentRequest;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\FileAttachmentRequest;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\PhotoAttachmentRequest;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\VideoAttachmentRequest;
use BushlanovDev\MaxMessengerBot\Models\BotInfo;
use BushlanovDev\MaxMessengerBot\Models\BotPatch;
use BushlanovDev\MaxMessengerBot\Models\Chat;
use BushlanovDev\MaxMessengerBot\Models\ChatAdmin;
use BushlanovDev\MaxMessengerBot\Models\ChatList;
use BushlanovDev\MaxMessengerBot\Models\ChatMember;
use BushlanovDev\MaxMessengerBot\Models\ChatMembersList;
use BushlanovDev\MaxMessengerBot\Models\ChatPatch;
use BushlanovDev\MaxMessengerBot\Models\Message;
use BushlanovDev\MaxMessengerBot\Models\MessageLink;
use BushlanovDev\MaxMessengerBot\Models\Result;
use BushlanovDev\MaxMessengerBot\Models\Subscription;
use BushlanovDev\MaxMessengerBot\Models\UpdateList;
use BushlanovDev\MaxMessengerBot\Models\UploadEndpoint;
use BushlanovDev\MaxMessengerBot\Models\VideoAttachmentDetails;
use InvalidArgumentException;
use JsonException;
use LogicException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionException;
use RuntimeException;

/**
 * The main entry point for interacting with the Max Bot API.
 * This class provides a clean, object-oriented interface over the raw HTTP API.
 *
 * @see https://dev.max.ru
 */
class Api
{
    const LIBRARY_VERSION = '1.4.2';

    const API_VERSION = '1.2.5';

    const API_BASE_URL = 'https://platform-api.max.ru';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH = 'PATCH';
    const METHOD_PUT = 'PUT';

    const ACTION_ME = '/me';
    const ACTION_SUBSCRIPTIONS = '/subscriptions';
    const ACTION_MESSAGES = '/messages';
    const ACTION_UPLOADS = '/uploads';
    const ACTION_CHATS = '/chats';
    const ACTION_CHATS_ACTIONS = '/chats/%d/actions';
    const ACTION_CHATS_PIN = '/chats/%d/pin';
    const ACTION_CHATS_MEMBERS_ME = '/chats/%d/members/me';
    const ACTION_CHATS_MEMBERS_ADMINS = '/chats/%d/members/admins';
    const ACTION_CHATS_MEMBERS_ADMINS_ID = '/chats/%d/members/admins/%d';
    const ACTION_CHATS_MEMBERS = '/chats/%d/members';
    const ACTION_UPDATES = '/updates';
    const ACTION_ANSWERS = '/answers';
    const ACTION_VIDEO_DETAILS = '/videos/%s';

    const RESUMABLE_UPLOAD_THRESHOLD_BYTES = 10 * 1024 * 1024; // 10 MB
    /**
     * @readonly
     * @var \BushlanovDev\MaxMessengerBot\ClientApiInterface
     */
    private $client;

    /**
     * @readonly
     * @var \BushlanovDev\MaxMessengerBot\ModelFactory
     */
    private $modelFactory;

    /**
     * @readonly
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @readonly
     * @var \BushlanovDev\MaxMessengerBot\UpdateDispatcher
     */
    private $updateDispatcher;

    /**
     * Api constructor.
     *
     * @param string|null $accessToken Your bot's access token from @MasterBot.
     * @param ClientApiInterface|null $client Http api client.
     * @param ModelFactory|null $modelFactory The model factory.
     * @param LoggerInterface|null $logger PSR LoggerInterface.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        $accessToken = null,
        $client = null,
        $modelFactory = null,
        $logger = null
    ) {
        if (empty($accessToken) && $client === null) {
            throw new InvalidArgumentException('You must provide either an access token or a client.');
        }

        $this->logger = isset($logger) ? $logger : new NullLogger();

        if ($client === null) {
            if (!class_exists(\GuzzleHttp\Client::class) || !class_exists(\GuzzleHttp\Psr7\HttpFactory::class)) {
                throw new LogicException(
                    'No client was provided and "guzzlehttp/guzzle" is not found. ' .
                    'Please run "composer require guzzlehttp/guzzle" or create and pass your own implementation of ClientApiInterface.'
                );
            }

            $guzzle = new \GuzzleHttp\Client([
                'timeout' => 10,
                'connect_timeout' => 5,
                'read_timeout' => 10,
                'headers' => ['User-Agent' => 'max-bot-api-client-php/' . self::LIBRARY_VERSION . ' PHP/' . PHP_VERSION],
            ]);
            $httpFactory = new \GuzzleHttp\Psr7\HttpFactory();
            $client = new Client(
                $accessToken,
                $guzzle,
                $httpFactory,
                $httpFactory,
                self::API_BASE_URL,
                null,
                $this->logger
            );
        }

        $this->client = $client;
        $this->modelFactory = isset($modelFactory) ? $modelFactory : new ModelFactory($this->logger);
        $this->updateDispatcher = new UpdateDispatcher($this);
    }

    /**
     * Performs a request to the Max Bot API.
     *
     * @param string $method The HTTP method (GET, POST, PATCH, etc.).
     * @param string $uri The API endpoint (e.g., '/me', '/messages').
     * @param array<string, mixed> $queryParams Query parameters for the request.
     * @param array<string, mixed> $body The request body.
     *
     * @return array<string, mixed> The decoded JSON response as an associative array.
     * @throws ClientApiException for API-level errors (4xx, 5xx).
     * @throws NetworkException for network-related issues.
     * @throws SerializationException for JSON encoding/decoding failures.
     * @codeCoverageIgnore
     */
    public function request($method, $uri, $queryParams = [], $body = [])
    {
        return $this->client->request($method, $uri, $queryParams, $body);
    }

    /**
     * Gets the central update dispatcher instance. Use this to register your event and command handlers.
     *
     * @return UpdateDispatcher
     * @codeCoverageIgnore
     */
    public function getUpdateDispatcher()
    {
        return $this->updateDispatcher;
    }

    /**
     * Creates a WebhookHandler instance, pre-configured with the necessary dependencies.
     *
     * @param string|null $secret The secret key for request verification.
     *
     * @return WebhookHandler
     */
    public function createWebhookHandler($secret = null)
    {
        return new WebhookHandler(
            $this->updateDispatcher,
            $this->modelFactory,
            $this->logger,
            $secret
        );
    }

    /**
     * Creates a LongPollingHandler instance, pre-configured for running a long-polling loop.
     *
     * @return LongPollingHandler
     */
    public function createLongPollingHandler()
    {
        return new LongPollingHandler(
            $this,
            $this->updateDispatcher,
            $this->logger
        );
    }

    /**
     * You can use this method for getting updates in case your bot is not subscribed to WebHook.
     * The method is based on long polling.
     *
     * @param int|null $limit Maximum number of updates to be retrieved (1-1000).
     * @param int|null $timeout Timeout in seconds for long polling (0-90).
     * @param int|null $marker Pass `null` to get updates you didn't get yet.
     * @param UpdateType[]|null $types Comma separated list of update types your bot want to receive.
     *
     * @return UpdateList
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getUpdates(
        $limit = null,
        $timeout = null,
        $marker = null,
        $types = null
    ) {
        $query = [
            'limit' => $limit,
            'timeout' => $timeout,
            'marker' => $marker,
            'types' => $types !== null ? implode(',', array_map(function ($type) {
                return $type;
            }, $types)) : null,
        ];

        return $this->modelFactory->createUpdateList(
            $this->client->request(
                self::METHOD_GET,
                self::ACTION_UPDATES,
                array_filter($query, function ($value) {
                    return $value !== null;
                })
            )
        );
    }

    /**
     * Information about the current bot, identified by an access token.
     *
     * @return BotInfo
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getBotInfo()
    {
        return $this->modelFactory->createBotInfo(
            $this->client->request(self::METHOD_GET, self::ACTION_ME)
        );
    }

    /**
     * List of all active webhook subscriptions.
     *
     * @return Subscription[]
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getSubscriptions()
    {
        return $this->modelFactory->createSubscriptions(
            $this->client->request(self::METHOD_GET, self::ACTION_SUBSCRIPTIONS)
        );
    }

    /**
     * Subscribes the bot to receive updates via WebHook.
     *
     * @param string $url URL webhook.
     * @param string|null $secret Secret key for verifying the authenticity of requests.
     * @param UpdateType[]|null $updateTypes List of update types.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function subscribe(
        $url,
        $secret = null,
        $updateTypes = null
    ) {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_POST,
                self::ACTION_SUBSCRIPTIONS,
                [],
                [
                    'url' => $url,
                    'secret' => $secret,
                    'update_types' => !empty($updateTypes) ? array_map(function ($type) {
                        return is_string($type) ? $type : $type->value;
                    }, $updateTypes) : null,
                ]
            )
        );
    }

    /**
     * Unsubscribes bot from receiving updates via WebHook.
     *
     * @param string $url URL webhook.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function unsubscribe($url)
    {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_DELETE,
                self::ACTION_SUBSCRIPTIONS,
                compact('url')
            )
        );
    }

    /**
     * Sends a message to a chat or user.
     *
     * @param int|null $userId Fill this parameter if you want to send message to user.
     * @param int|null $chatId Fill this if you send message to chat.
     * @param string|null $text Message text.
     * @param AbstractAttachmentRequest[]|null $attachments Message attachments.
     * @param MessageFormat|null $format Message format.
     * @param MessageLink|null $link Link to message.
     * @param bool $notify If false, chat participants would not be notified.
     * @param bool $disableLinkPreview If false, server will not generate media preview for links in text.
     *
     * @return Message
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function sendMessage(
        $userId = null,
        $chatId = null,
        $text = null,
        $attachments = null,
        $format = null,
        $link = null,
        $notify = true,
        $disableLinkPreview = false
    ) {
        $query = [
            'user_id' => $userId,
            'chat_id' => $chatId,
            'disable_link_preview' => $disableLinkPreview,
        ];

        $response = $this->client->request(
            self::METHOD_POST,
            self::ACTION_MESSAGES,
            array_filter($query, function ($item) {
                return null !== $item;
            }),
            $this->buildNewMessageBody($text, $attachments, $format, $link, $notify)
        );

        return $this->modelFactory->createMessageFromSendResponse($response);
    }

    /**
     * Sends a message to a user.
     *
     * @param int|null $userId Fill this parameter if you want to send message to user.
     * @param string|null $text Message text.
     * @param AbstractAttachmentRequest[]|null $attachments Message attachments.
     * @param MessageFormat|null $format Message format.
     * @param MessageLink|null $link Link to message.
     * @param bool $notify If false, chat participants would not be notified.
     * @param bool $disableLinkPreview If false, server will not generate media preview for links in text.
     *
     * @return Message
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     * @codeCoverageIgnore
     */
    public function sendUserMessage(
        $userId = null,
        $text = null,
        $attachments = null,
        $format = null,
        $link = null,
        $notify = true,
        $disableLinkPreview = false
    ) {
        return $this->sendMessage($userId, null, $text, $attachments, $format, $link, $notify, $disableLinkPreview);
    }

    /**
     * Sends a message to a chat.
     *
     * @param int|null $chatId Fill this if you send message to chat.
     * @param string|null $text Message text.
     * @param AbstractAttachmentRequest[]|null $attachments Message attachments.
     * @param MessageFormat|null $format Message format.
     * @param MessageLink|null $link Link to message.
     * @param bool $notify If false, chat participants would not be notified.
     * @param bool $disableLinkPreview If false, server will not generate media preview for links in text.
     *
     * @return Message
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     * @codeCoverageIgnore
     */
    public function sendChatMessage(
        $chatId = null,
        $text = null,
        $attachments = null,
        $format = null,
        $link = null,
        $notify = true,
        $disableLinkPreview = false
    ) {
        return $this->sendMessage(null, $chatId, $text, $attachments, $format, $link, $notify, $disableLinkPreview);
    }

    /**
     * Returns the URL for the subsequent file upload.
     *
     * @param UploadType $type Uploaded file type.
     *
     * @return UploadEndpoint Endpoint you should upload to your binaries.
     * @throws ReflectionException
     */
    public function getUploadUrl($type)
    {
        return $this->modelFactory->createUploadEndpoint(
            $this->client->request(
                self::METHOD_POST,
                self::ACTION_UPLOADS,
                ['type' => $type]
            )
        );
    }

    /**
     * Uploads a file to the specified URL.
     *
     * @param string $uploadUrl The target URL for the upload.
     * @param mixed $fileHandle A stream resource pointing to the file.
     * @param string $fileName The desired file name for the upload.
     *
     * @return string The body of the final response from the server.
     * @throws ClientApiException
     * @throws NetworkException
     * @throws SerializationException
     * @throws RuntimeException
     */
    public function uploadFile($uploadUrl, $fileHandle, $fileName)
    {
        $stat = fstat($fileHandle);
        if (!is_array($stat)) {
            throw new RuntimeException('File handle is not a valid resource.');
        }

        rewind($fileHandle);

        if ($stat['size'] < self::RESUMABLE_UPLOAD_THRESHOLD_BYTES) {
            return $this->client->multipartUpload($uploadUrl, $fileHandle, $fileName);
        }

        return $this->client->resumableUpload($uploadUrl, $fileHandle, $fileName, $stat['size']);
    }

    /**
     * A simplified method for uploading a file and getting the resulting attachment object.
     *
     * @param UploadType $type Uploaded file type.
     * @param string $filePath Path to the file on the local disk.
     *
     * @return AbstractAttachmentRequest
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws LogicException
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function uploadAttachment($type, $filePath)
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new InvalidArgumentException("File not found or not readable: $filePath");
        }

        $fileHandle = @fopen($filePath, 'r');
        if ($fileHandle === false) {
            throw new RuntimeException("Could not open file for reading: $filePath");
        }

        $uploadEndpoint = $this->getUploadUrl($type);

        // For audio and video, the token is received *before* the upload
        // The actual upload response is not JSON and can be ignored on success
        if ($type === UploadType::Audio || $type === UploadType::Video) {
            if (empty($uploadEndpoint->token)) {
                throw new SerializationException(
                    "API did not return a pre-upload token for type '$type'."
                );
            }

            $this->uploadFile($uploadEndpoint->url, $fileHandle, basename($filePath));
            fclose($fileHandle);

            switch ($type) {
                case UploadType::Audio:
                    return new AudioAttachmentRequest($uploadEndpoint->token);
                case UploadType::Video:
                    return new VideoAttachmentRequest($uploadEndpoint->token);
            }
        }

        // For images and files, the token is in the response *after* the upload.
        $responseBody = $this->uploadFile($uploadEndpoint->url, $fileHandle, basename($filePath));
        fclose($fileHandle);

        try {
            $uploadResult = json_decode($responseBody, true, 512, 0);
        } catch (\Exception $e) {
            throw new SerializationException('Failed to decode upload server response JSON.', 0, $e);
        }

        // Using switch because match expression arms cannot be code blocks.
        switch ($type) {
            case UploadType::Image:
                $photoData = current(isset($uploadResult['photos']) ? $uploadResult['photos'] : []); // Get first photo from response
                if (!isset($photoData['token'])) {
                    throw new SerializationException('Could not find "token" in photo upload response.');
                }
                return PhotoAttachmentRequest::fromToken($photoData['token']);
            case UploadType::File:
                if (!isset($uploadResult['token'])) {
                    throw new SerializationException('Could not find "token" in file upload response.');
                }
                return new FileAttachmentRequest($uploadResult['token']);
        }

        // @codeCoverageIgnoreStart
        throw new LogicException("Attachment creation for type '$type' is not yet implemented."); // @phpstan-ignore-line
        // @codeCoverageIgnoreEnd
    }

    /**
     * Returns info about chat.
     *
     * @param int $chatId Requested chat identifier.
     *
     * @return Chat
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getChat($chatId)
    {
        return $this->modelFactory->createChat(
            $this->client->request(self::METHOD_GET, self::ACTION_CHATS . '/' . $chatId)
        );
    }

    /**
     * Returns chat/channel information by its public link or a dialog with a user by their username.
     * The link should be prefixed with '@' or can be passed without it.
     *
     * @param string $chatLink Public chat link (e.g., '@mychannel') or username (e.g., '@john_doe').
     *
     * @return Chat
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getChatByLink($chatLink)
    {
        return $this->modelFactory->createChat(
            $this->client->request(
                self::METHOD_GET,
                self::ACTION_CHATS . '/' . $chatLink
            )
        );
    }

    /**
     * Returns information about chats that the bot participated in. The result is a paginated list.
     *
     * @param int|null $count Number of chats requested (1-100, default 50).
     * @param int|null $marker Points to the next data page. Use null for the first page.
     *
     * @return ChatList
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getChats($count = null, $marker = null)
    {
        $query = [
            'count' => $count,
            'marker' => $marker,
        ];

        return $this->modelFactory->createChatList(
            $this->client->request(
                self::METHOD_GET,
                self::ACTION_CHATS,
                array_filter($query, function ($value) {
                    return $value !== null;
                })
            )
        );
    }

    /**
     * Deletes a chat for all participants. The bot must have appropriate permissions.
     *
     * @param int $chatId Chat identifier to delete.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function deleteChat($chatId)
    {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_DELETE,
                self::ACTION_CHATS . '/' . $chatId
            )
        );
    }

    /**
     * Sends a specific action to a chat, such as 'typing...'. This is used to show bot activity to the user.
     *
     * @param int $chatId The identifier of the target chat.
     * @param SenderAction $action The action to be sent.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function sendAction($chatId, $action)
    {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_POST,
                sprintf(self::ACTION_CHATS_ACTIONS, $chatId),
                [],
                ['action' => $action->value]
            )
        );
    }

    /**
     * Gets the pinned message in a chat or channel.
     *
     * @param int $chatId Identifier of the chat to get its pinned message from.
     *
     * @return Message|null
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getPinnedMessage($chatId)
    {
        $response = $this->client->request(
            self::METHOD_GET,
            sprintf(self::ACTION_CHATS_PIN, $chatId)
        );

        if (!isset($response['message']) || empty($response['message'])) {
            return null;
        }

        return $this->modelFactory->createMessage($response['message']);
    }

    /**
     * Unpins a message in a chat or channel.
     *
     * @param int $chatId Chat identifier to remove the pinned message from.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function unpinMessage($chatId)
    {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_DELETE,
                sprintf(self::ACTION_CHATS_PIN, $chatId)
            )
        );
    }

    /**
     * Returns chat membership info for the current bot.
     *
     * @param int $chatId Chat identifier.
     *
     * @return ChatMember
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getMembership($chatId)
    {
        return $this->modelFactory->createChatMember(
            $this->client->request(
                self::METHOD_GET,
                sprintf(self::ACTION_CHATS_MEMBERS_ME, $chatId)
            )
        );
    }

    /**
     * Removes the bot from a chat's members.
     *
     * @param int $chatId Chat identifier to leave from.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function leaveChat($chatId)
    {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_DELETE,
                sprintf(self::ACTION_CHATS_MEMBERS_ME, $chatId)
            )
        );
    }

    /**
     * Returns messages in a chat. Messages are traversed in reverse chronological order.
     *
     * @param int $chatId Identifier of the chat to get messages from.
     * @param string[]|null $messageIds A comma-separated list of message IDs to retrieve.
     * @param int|null $from Start time (Unix timestamp in ms) for the requested messages.
     * @param int|null $to End time (Unix timestamp in ms) for the requested messages.
     * @param int|null $count Maximum amount of messages in the response (1-100, default 50).
     *
     * @return Message[]
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getMessages(
        $chatId,
        $messageIds = null,
        $from = null,
        $to = null,
        $count = null
    ) {
        $query = [
            'chat_id' => $chatId,
            'message_ids' => $messageIds !== null ? implode(',', $messageIds) : null,
            'from' => $from,
            'to' => $to,
            'count' => $count,
        ];

        $response = $this->client->request(
            self::METHOD_GET,
            self::ACTION_MESSAGES,
            array_filter($query, function ($value) {
                return $value !== null;
            })
        );

        return $this->modelFactory->createMessages($response);
    }

    /**
     * Deletes a message in a dialog or in a chat if the bot has permission to delete messages.
     *
     * @param string $messageId Identifier of the message to be deleted.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function deleteMessage($messageId)
    {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_DELETE,
                self::ACTION_MESSAGES,
                ['message_id' => $messageId]
            )
        );
    }

    /**
     * Returns a single message by its identifier.
     *
     * @param string $messageId Message identifier (`mid`) to get.
     *
     * @return Message
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getMessageById($messageId)
    {
        return $this->modelFactory->createMessage(
            $this->client->request(
                self::METHOD_GET,
                self::ACTION_MESSAGES . '/' . $messageId
            )
        );
    }

    /**
     * Pins a message in a chat or channel.
     *
     * @param int $chatId Chat identifier where the message should be pinned.
     * @param string $messageId Identifier of the message to pin.
     * @param bool $notify If true, participants will be notified with a system message.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function pinMessage($chatId, $messageId, $notify = true)
    {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_PUT,
                sprintf(self::ACTION_CHATS_PIN, $chatId),
                [],
                [
                    'message_id' => $messageId,
                    'notify' => $notify,
                ]
            )
        );
    }

    /**
     * Returns all chat administrators. The bot must be an administrator in the requested chat.
     *
     * @param int $chatId Chat identifier.
     *
     * @return ChatMembersList
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getAdmins($chatId)
    {
        return $this->modelFactory->createChatMembersList(
            $this->client->request(
                self::METHOD_GET,
                sprintf(self::ACTION_CHATS_MEMBERS_ADMINS, $chatId)
            )
        );
    }

    /**
     * Returns a paginated list of users who are participating in a chat.
     *
     * @param int $chatId The identifier of the chat.
     * @param int[]|null $userIds A list of user identifiers to get their specific membership.
     *                            When this parameter is passed, `count` and `marker` are ignored.
     * @param int|null $marker The pagination marker to get the next page of members.
     * @param int|null $count The number of members to return (1-100, default is 20).
     *
     * @return ChatMembersList
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getMembers(
        $chatId,
        $userIds = null,
        $marker = null,
        $count = null
    ) {
        $query = [
            'user_ids' => $userIds !== null ? implode(',', $userIds) : null,
            'marker' => $marker,
            'count' => $count,
        ];

        return $this->modelFactory->createChatMembersList(
            $this->client->request(
                self::METHOD_GET,
                sprintf(self::ACTION_CHATS_MEMBERS, $chatId),
                array_filter($query, function ($value) {
                    return $value !== null;
                })
            )
        );
    }

    /**
     * Revokes admin rights from a user in the chat.
     *
     * @param int $chatId The identifier of the chat.
     * @param int $userId The identifier of the user to revoke admin rights from.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function deleteAdmin($chatId, $userId)
    {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_DELETE,
                sprintf(self::ACTION_CHATS_MEMBERS_ADMINS_ID, $chatId, $userId)
            )
        );
    }

    /**
     * Removes a member from a chat. The bot may require additional permissions.
     *
     * @param int $chatId The identifier of the chat.
     * @param int $userId The identifier of the user to remove.
     * @param bool $block Set to true if the user should also be blocked in the chat.
     *                    Applicable only for chats with a public or private link.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function deleteMember($chatId, $userId, $block = false)
    {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_DELETE,
                sprintf(self::ACTION_CHATS_MEMBERS, $chatId),
                [
                    'user_id' => $userId,
                    'block' => $block,
                ]
            )
        );
    }

    /**
     * Sets the administrators for a chat.
     *
     * @param int $chatId The identifier of the chat.
     * @param ChatAdmin[] $admins An array of ChatAdmin objects representing the users and their permissions.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function addAdmins($chatId, $admins)
    {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_POST,
                sprintf(self::ACTION_CHATS_MEMBERS_ADMINS, $chatId),
                [],
                ['admins' => array_map(function (ChatAdmin $admin) {
                    return $admin->toArray();
                }, $admins)]
            )
        );
    }

    /**
     * Adds members to a chat. The bot may require additional permissions.
     *
     * @param int $chatId The identifier of the chat.
     * @param int[] $userIds An array of user identifiers to add to the chat.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function addMembers($chatId, $userIds)
    {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_POST,
                sprintf(self::ACTION_CHATS_MEMBERS, $chatId),
                [],
                ['user_ids' => $userIds]
            )
        );
    }

    /**
     * Sends an answer to a callback query. This should be called after a user clicks an inline button.
     *
     * @param string $callbackId The identifier of the callback query.
     * @param string|null $notification A short text notification to show to the user.
     * @param string|null $text If provided, the original message will be edited with this text.
     * @param AbstractAttachmentRequest[]|null $attachments New attachments for the edited message.
     * @param MessageLink|null $link New link for the edited message.
     * @param MessageFormat|null $format Formatting for the new message text.
     * @param bool $notify Notification setting for the edited message.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function answerOnCallback(
        $callbackId,
        $notification = null,
        $text = null,
        $attachments = null,
        $link = null,
        $format = null,
        $notify = true
    ) {
        $answerBody = ['notification' => $notification];
        if ($text !== null || $attachments !== null || $link !== null) {
            $answerBody['message'] = $this->buildNewMessageBody($text, $attachments, $format, $link, $notify);
        }

        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_POST,
                self::ACTION_ANSWERS,
                ['callback_id' => $callbackId],
                array_filter($answerBody, function ($value) {
                    return $value !== null;
                })
            )
        );
    }

    /**
     * Edits a message that was previously sent by the bot.
     * Note on attachments:
     * - To leave attachments unchanged, pass `null` (default).
     * - To remove all attachments, pass an empty array `[]`.
     *
     * @param string $messageId The identifier of the message to edit.
     * @param string|null $text New message text.
     * @param AbstractAttachmentRequest[]|null $attachments New message attachments.
     * @param MessageFormat|null $format Formatting for the new message text.
     * @param MessageLink|null $link New link for the edited message.
     * @param bool $notify Notification setting for the edited message.
     *
     * @return Result
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function editMessage(
        $messageId,
        $text = null,
        $attachments = null,
        $format = null,
        $link = null,
        $notify = true
    ) {
        return $this->modelFactory->createResult(
            $this->client->request(
                self::METHOD_PUT,
                self::ACTION_MESSAGES,
                ['message_id' => $messageId],
                $this->buildNewMessageBody($text, $attachments, $format, $link, $notify)
            )
        );
    }

    /**
     * Edits the bot info.
     *
     * Example: editBotInfo(new BotPatch(name: 'New Bot Name', description: null));
     *
     * @param BotPatch $botPatch
     *
     * @return BotInfo
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function editBotInfo($botPatch)
    {
        return $this->modelFactory->createBotInfo(
            $this->client->request(
                self::METHOD_PATCH,
                self::ACTION_ME,
                [],
                $botPatch->toArray()
            )
        );
    }

    /**
     * Edits chat info such as title, icon, etc.
     * Instantiate ChatPatch with named arguments for the fields you want to change.
     *
     * Example:
     * $patch = new ChatPatch(title: 'New Cool Title');
     * $api->editChat(12345, $patch);
     *
     * @param int $chatId The identifier of the chat to edit.
     * @param ChatPatch $chatPatch An object containing the fields to update.
     *
     * @return Chat
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function editChat($chatId, $chatPatch)
    {
        return $this->modelFactory->createChat(
            $this->client->request(
                self::METHOD_PATCH,
                self::ACTION_CHATS . '/' . $chatId,
                [],
                $chatPatch->toArray()
            )
        );
    }

    /**
     * Returns detailed information about a video attachment, including playback URLs.
     *
     * @param string $videoToken The token of the video attachment.
     *
     * @return VideoAttachmentDetails
     * @throws ClientApiException
     * @throws NetworkException
     * @throws ReflectionException
     * @throws SerializationException
     */
    public function getVideoAttachmentDetails($videoToken)
    {
        return $this->modelFactory->createVideoAttachmentDetails(
            $this->client->request(
                self::METHOD_GET,
                sprintf(self::ACTION_VIDEO_DETAILS, $videoToken)
            )
        );
    }

    /**
     * A helper to build the 'NewMessageBody' array structure consistently.
     *
     * @param string|null $text
     * @param AbstractAttachmentRequest[]|null $attachments
     * @param MessageFormat|null $format
     * @param MessageLink|null $link
     * @param bool $notify
     *
     * @return array<string, mixed>
     * @throws ReflectionException
     * @param ?\BushlanovDev\MaxMessengerBot\Enums\MessageFormat::* $format
     */
    private function buildNewMessageBody(
        $text,
        $attachments,
        $format,
        $link,
        $notify
    ) {

        $notify = (bool) $notify;
        $body = [
            'text' => $text,
            'format' => $format,
            'notify' => $notify,
            'link' => $link,
            'attachments' => $attachments !== null ? array_map(
                function (AbstractModel $attachment) {
                    return $attachment->toArray();
                },
                $attachments
            ) : null,
        ];

        return array_filter($body, function ($item) {
            return $item !== null;
        });
    }
}
