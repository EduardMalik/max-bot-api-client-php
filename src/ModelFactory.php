<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Enums\InlineButtonType;
use BushlanovDev\MaxMessengerBot\Enums\MarkupType;
use BushlanovDev\MaxMessengerBot\Enums\ReplyButtonType;
use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\AbstractAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\AudioAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\AbstractInlineButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\CallbackButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\ChatButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\LinkButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\OpenAppButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\RequestContactButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\RequestGeoLocationButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\AbstractReplyButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendContactButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendGeoLocationButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendMessageButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\ContactAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\DataAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\FileAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\InlineKeyboardAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\LocationAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\PhotoAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\ReplyKeyboardAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\ShareAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\StickerAttachment;
use BushlanovDev\MaxMessengerBot\Models\Attachments\VideoAttachment;
use BushlanovDev\MaxMessengerBot\Models\BotInfo;
use BushlanovDev\MaxMessengerBot\Models\Chat;
use BushlanovDev\MaxMessengerBot\Models\ChatList;
use BushlanovDev\MaxMessengerBot\Models\ChatMember;
use BushlanovDev\MaxMessengerBot\Models\ChatMembersList;
use BushlanovDev\MaxMessengerBot\Models\Markup\AbstractMarkup;
use BushlanovDev\MaxMessengerBot\Models\Markup\EmphasizedMarkup;
use BushlanovDev\MaxMessengerBot\Models\Markup\HeadingMarkup;
use BushlanovDev\MaxMessengerBot\Models\Markup\HighlightedMarkup;
use BushlanovDev\MaxMessengerBot\Models\Markup\LinkMarkup;
use BushlanovDev\MaxMessengerBot\Models\Markup\MonospacedMarkup;
use BushlanovDev\MaxMessengerBot\Models\Markup\StrikethroughMarkup;
use BushlanovDev\MaxMessengerBot\Models\Markup\StrongMarkup;
use BushlanovDev\MaxMessengerBot\Models\Markup\UnderlineMarkup;
use BushlanovDev\MaxMessengerBot\Models\Markup\UserMentionMarkup;
use BushlanovDev\MaxMessengerBot\Models\Message;
use BushlanovDev\MaxMessengerBot\Models\MessageBody;
use BushlanovDev\MaxMessengerBot\Models\Result;
use BushlanovDev\MaxMessengerBot\Models\Subscription;
use BushlanovDev\MaxMessengerBot\Models\UpdateList;
use BushlanovDev\MaxMessengerBot\Models\Updates\AbstractUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\BotAddedToChatUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\BotRemovedFromChatUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\BotStartedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\BotStoppedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\ChatTitleChangedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\DialogClearedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\DialogMutedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\DialogRemovedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\DialogUnmutedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageCallbackUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageChatCreatedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageCreatedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageEditedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageRemovedUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\UserAddedToChatUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\UserRemovedFromChatUpdate;
use BushlanovDev\MaxMessengerBot\Models\UploadEndpoint;
use BushlanovDev\MaxMessengerBot\Models\VideoAttachmentDetails;
use LogicException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionException;

/**
 * Creates DTOs from raw associative arrays returned by the API client.
 */
class ModelFactory
{
    /**
     * @readonly
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface|null $logger PSR LoggerInterface.
     */
    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * Simple response to request.
     *
     * @param array<string, mixed> $data
     *
     * @return Result
     * @throws ReflectionException
     */
    public function createResult($data): Result
    {
        return Result::fromArray($data);
    }

    /**
     * Information about the current bot.
     *
     * @param array<string, mixed> $data
     *
     * @return BotInfo
     * @throws ReflectionException
     */
    public function createBotInfo($data): BotInfo
    {
        return BotInfo::fromArray($data);
    }

    /**
     * Information about webhook subscription.
     *
     * @param array<string, mixed> $data
     *
     * @return Subscription
     * @throws ReflectionException
     */
    public function createSubscription($data): Subscription
    {
        return Subscription::fromArray($data);
    }

    /**
     * List of all active webhook subscriptions.
     *
     * @param array<string, mixed> $data
     *
     * @return Subscription[]
     * @throws ReflectionException
     */
    public function createSubscriptions($data): array
    {
        return isset($data['subscriptions']) && is_array($data['subscriptions'])
            ? array_map([$this, 'createSubscription'], $data['subscriptions'])
            : [];
    }

    /**
     * Creates a Message from the specific response structure of the sendMessage endpoint.
     *
     * @param array<string, mixed> $data The raw response from the client.
     *
     * @return Message
     * @throws ReflectionException
     */
    public function createMessageFromSendResponse($data): Message
    {
        $messageData = $data['message'];

        $topLevelData = [
            'chat_id' => $data['chat_id'] ?? null,
            'recipient_id' => $data['recipient_id'] ?? null,
            'message_id' => $data['message_id'] ?? null,
        ];
        $messageData = array_merge($messageData, array_filter($topLevelData, function ($value) {
            return $value !== null;
        }));

        if (isset($messageData['message']) && is_array($messageData['message'])) {
            $messageData['body'] = $messageData['message'];
            unset($messageData['message']);
        }

        return $this->createMessage($messageData);
    }

    /**
     * Message.
     *
     * @param array<string, mixed> $data
     *
     * @return Message
     * @throws ReflectionException
     */
    public function createMessage($data): Message
    {
        if (isset($data['body']) && is_array($data['body'])) {
            $data['body'] = $this->createMessageBody($data['body']);
        }

        return Message::fromArray($data);
    }

    /**
     * List of messages.
     *
     * @param array<string, mixed> $data
     *
     * @return Message[]
     */
    public function createMessages($data): array
    {
        return isset($data['messages']) && is_array($data['messages'])
            ? array_map([$this, 'createMessage'], $data['messages'])
            : [];
    }

    /**
     * Creates a MessageBody object from raw API data, handling polymorphic attachments and markup.
     *
     * @param array<string, mixed> $data
     *
     * @return MessageBody
     * @throws ReflectionException
     */
    private function createMessageBody(array $data): MessageBody
    {
        if (isset($data['attachments']) && is_array($data['attachments'])) {
            $data['attachments'] = array_map(
                [$this, 'createAttachment'],
                $data['attachments']
            );
        }

        if (isset($data['markup']) && is_array($data['markup'])) {
            $data['markup'] = array_map(
                [$this, 'createMarkupElement'],
                $data['markup']
            );
        }

        return MessageBody::fromArray($data);
    }

    /**
     * Creates a specific Attachment model based on the 'type' field.
     *
     * @param array<string, mixed> $data
     *
     * @return AbstractAttachment
     * @throws ReflectionException
     */
    public function createAttachment($data): AbstractAttachment
    {
        $attachmentType = AttachmentType::fromName($data['type'] ?? '');
        if ($attachmentType === AttachmentType::ReplyKeyboard
            && isset($data['buttons']) && is_array($data['buttons'])) {
            $data['buttons'] = array_map(
                function ($rowOfButtons) {
                    return array_map([$this, 'createReplyButton'], $rowOfButtons);
                },
                $data['buttons']
            );
        }

        if ($attachmentType === AttachmentType::InlineKeyboard
            && isset($data['payload']['buttons']) && is_array($data['payload']['buttons'])) {
            $data['payload']['buttons'] = array_map(
                function ($rowOfButtons) {
                    return array_map([$this, 'createInlineButton'], $rowOfButtons);
                },
                $data['payload']['buttons']
            );
        }

        switch ($attachmentType) {
            case AttachmentType::Data:
                return DataAttachment::fromArray($data);
            case AttachmentType::Share:
                return ShareAttachment::fromArray($data);
            case AttachmentType::Image:
                return PhotoAttachment::fromArray($data);
            case AttachmentType::Video:
                return VideoAttachment::fromArray($data);
            case AttachmentType::Audio:
                return AudioAttachment::fromArray($data);
            case AttachmentType::File:
                return FileAttachment::fromArray($data);
            case AttachmentType::Sticker:
                return StickerAttachment::fromArray($data);
            case AttachmentType::Contact:
                return ContactAttachment::fromArray($data);
            case AttachmentType::InlineKeyboard:
                return InlineKeyboardAttachment::fromArray($data);
            case AttachmentType::ReplyKeyboard:
                return ReplyKeyboardAttachment::fromArray($data);
            case AttachmentType::Location:
                return LocationAttachment::fromArray($data);
            default:
                throw new LogicException('Unknown or unsupported attachment type: ' . ($data['type'] ?? 'none'));
        }
    }

    /**
     * Creates a specific ReplyButton model based on the 'type' field.
     *
     * @param array<string, mixed> $data
     *
     * @return AbstractReplyButton
     * @throws ReflectionException
     * @throws LogicException
     */
    public function createReplyButton($data): AbstractReplyButton
    {
        switch (ReplyButtonType::fromName($data['type'] ?? '')) {
            case ReplyButtonType::Message:
                return SendMessageButton::fromArray($data);
            case ReplyButtonType::UserContact:
                return SendContactButton::fromArray($data);
            case ReplyButtonType::UserGeoLocation:
                return SendGeoLocationButton::fromArray($data);
            default:
                throw new LogicException(
                    'Unknown or unsupported reply button type: ' . ($data['type'] ?? 'none')
                );
        }
    }

    /**
     * Creates a specific InlineButton model based on the 'type' field.
     *
     * @param array<string, mixed> $data
     * @return AbstractInlineButton
     * @throws ReflectionException
     * @throws LogicException
     */
    public function createInlineButton($data): AbstractInlineButton
    {
        switch (InlineButtonType::fromName($data['type'] ?? '')) {
            case InlineButtonType::Callback:
                return CallbackButton::fromArray($data);
            case InlineButtonType::Link:
                return LinkButton::fromArray($data);
            case InlineButtonType::RequestContact:
                return RequestContactButton::fromArray($data);
            case InlineButtonType::RequestGeoLocation:
                return RequestGeoLocationButton::fromArray($data);
            case InlineButtonType::Chat:
                return ChatButton::fromArray($data);
            case InlineButtonType::OpenApp:
                return OpenAppButton::fromArray($data);
            default:
                throw new LogicException(
                    'Unknown or unsupported inline button type: ' . ($data['type'] ?? 'none')
                );
        }
    }

    /**
     * Endpoint you should upload to your binaries.
     *
     * @param array<string, mixed> $data
     *
     * @return UploadEndpoint
     * @throws ReflectionException
     */
    public function createUploadEndpoint($data): UploadEndpoint
    {
        return UploadEndpoint::fromArray($data);
    }

    /**
     * Chat information.
     *
     * @param array<string, mixed> $data
     *
     * @return Chat
     * @throws ReflectionException
     */
    public function createChat($data): Chat
    {
        return Chat::fromArray($data);
    }

    /**
     * Creates a list of updates from a raw API response.
     *
     * @param array<string, mixed> $data Raw response data.
     *
     * @return UpdateList
     * @throws ReflectionException
     * @throws LogicException
     */
    public function createUpdateList($data): UpdateList
    {
        $updateObjects = [];
        if (isset($data['updates']) && is_array($data['updates'])) {
            foreach ($data['updates'] as $updateData) {
                // Here we delegate the creation of a specific update to another factory method
                try {
                    $updateObjects[] = $this->createUpdate($updateData);
                } catch (LogicException $e) {
                    $this->logger->debug($e->getMessage(), ['payload' => $updateData, 'exception' => $e]);
                }
            }
        }

        return new UpdateList(
            $updateObjects,
            $data['marker'] ? (int)$data['marker'] : null
        );
    }

    /**
     * Creates a specific Update model based on the 'update_type' field.
     *
     * @param array<string, mixed> $data Raw data for a single update.
     *
     * @return AbstractUpdate
     * @throws ReflectionException
     * @throws LogicException
     */
    public function createUpdate($data): AbstractUpdate
    {
        switch (UpdateType::fromName($data['update_type'] ?? '')) {
            case UpdateType::MessageCreated:
                return MessageCreatedUpdate::fromArray($data);
            case UpdateType::MessageCallback:
                return MessageCallbackUpdate::fromArray($data);
            case UpdateType::MessageEdited:
                return MessageEditedUpdate::fromArray($data);
            case UpdateType::MessageRemoved:
                return MessageRemovedUpdate::fromArray($data);
            case UpdateType::BotAdded:
                return BotAddedToChatUpdate::fromArray($data);
            case UpdateType::BotRemoved:
                return BotRemovedFromChatUpdate::fromArray($data);
            case UpdateType::DialogMuted:
                return DialogMutedUpdate::fromArray($data);
            case UpdateType::DialogUnmuted:
                return DialogUnmutedUpdate::fromArray($data);
            case UpdateType::DialogCleared:
                return DialogClearedUpdate::fromArray($data);
            case UpdateType::DialogRemoved:
                return DialogRemovedUpdate::fromArray($data);
            case UpdateType::UserAdded:
                return UserAddedToChatUpdate::fromArray($data);
            case UpdateType::UserRemoved:
                return UserRemovedFromChatUpdate::fromArray($data);
            case UpdateType::BotStarted:
                return BotStartedUpdate::fromArray($data);
            case UpdateType::BotStopped:
                return BotStoppedUpdate::fromArray($data);
            case UpdateType::ChatTitleChanged:
                return ChatTitleChangedUpdate::fromArray($data);
            case UpdateType::MessageChatCreated:
                return MessageChatCreatedUpdate::fromArray($data);
            default:
                throw new LogicException(
                    'Unknown or unsupported update type received: ' . ($data['update_type'] ?? 'none')
                );
        }
    }

    /**
     * Information about chat list.
     *
     * @param array<string, mixed> $data
     *
     * @return ChatList
     * @throws ReflectionException
     */
    public function createChatList($data): ChatList
    {
        return ChatList::fromArray($data);
    }

    /**
     * Creates a ChatMember object from raw API data.
     *
     * @param array<string, mixed> $data
     *
     * @return ChatMember
     * @throws ReflectionException
     */
    public function createChatMember($data): ChatMember
    {
        return ChatMember::fromArray($data);
    }

    /**
     * Creates a ChatMembersList object from raw API data.
     *
     * @param array<string, mixed> $data
     *
     * @return ChatMembersList
     * @throws ReflectionException
     */
    public function createChatMembersList($data): ChatMembersList
    {
        return ChatMembersList::fromArray($data);
    }

    /**
     * Creates a VideoAttachmentDetails object from raw API data.
     *
     * @param array<string, mixed> $data
     *
     * @return VideoAttachmentDetails
     * @throws ReflectionException
     */
    public function createVideoAttachmentDetails($data): VideoAttachmentDetails
    {
        return VideoAttachmentDetails::fromArray($data);
    }

    /**
     * Creates a specific Markup model based on the 'type' field.
     *
     * @param array<string, mixed> $data
     *
     * @return AbstractMarkup
     * @throws ReflectionException
     */
    public function createMarkupElement($data): AbstractMarkup
    {
        switch (MarkupType::fromName($data['type'] ?? '')) {
            case MarkupType::Strong:
                return StrongMarkup::fromArray($data);
            case MarkupType::Emphasized:
                return EmphasizedMarkup::fromArray($data);
            case MarkupType::Monospaced:
                return MonospacedMarkup::fromArray($data);
            case MarkupType::Strikethrough:
                return StrikethroughMarkup::fromArray($data);
            case MarkupType::Underline:
                return UnderlineMarkup::fromArray($data);
            case MarkupType::Heading:
                return HeadingMarkup::fromArray($data);
            case MarkupType::Highlighted:
                return HighlightedMarkup::fromArray($data);
            case MarkupType::Link:
                return LinkMarkup::fromArray($data);
            case MarkupType::UserMention:
                return UserMentionMarkup::fromArray($data);
            default:
                throw new LogicException(
                    'Unknown or unsupported markup type: ' . ($data['type'] ?? 'none')
                );
        }
    }
}
