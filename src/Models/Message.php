<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Message.
 */
final class Message extends AbstractModel
{
    /**
     * @var int
     * @readonly
     */
    public $timestamp;
    /**
     * @var Recipient
     * @readonly
     */
    public $recipient;
    /**
     * @var MessageBody|null
     * @readonly
     */
    public $body;
    /**
     * @var User|null
     * @readonly
     */
    public $sender;
    /**
     * @var string|null
     * @readonly
     */
    public $url;
    /**
     * @var LinkedMessage|null
     * @readonly
     */
    public $link;
    /**
     * @var MessageStat|null
     * @readonly
     */
    public $stat;
    /**
     * @var int|null
     * @readonly
     */
    public $chatId;
    /**
     * @var int|null
     * @readonly
     */
    public $recipientId;
    /**
     * @var string|null
     * @readonly
     */
    public $messageId;
    /**
     * @param int $timestamp Unix-time when message was created.
     * @param Recipient $recipient Message recipient. Could be user or chat.
     * @param MessageBody|null $body Body of created message. Text + attachments.
     * @param User|null $sender User who sent this message. Can be null if message has been posted on behalf of a channel.
     * @param string|null $url Message public URL. Can be null for dialogs or non-public chats/channels.
     * @param LinkedMessage|null $link Forwarded or replied message.
     * @param MessageStat|null $stat Message statistics. Available only for channels.
     * @param int|null $chatId Chat identifier.
     * @param int|null $recipientId User identifier, if message was sent to user.
     * @param string|null $messageId Unique identifier of message.
     */
    public function __construct(int $timestamp, Recipient $recipient, ?MessageBody $body, ?User $sender, ?string $url, ?LinkedMessage $link, ?MessageStat $stat, ?int $chatId = null, ?int $recipientId = null, ?string $messageId = null)
    {
        $this->timestamp = $timestamp;
        $this->recipient = $recipient;
        $this->body = $body;
        $this->sender = $sender;
        $this->url = $url;
        $this->link = $link;
        $this->stat = $stat;
        $this->chatId = $chatId;
        $this->recipientId = $recipientId;
        $this->messageId = $messageId;
    }
}
