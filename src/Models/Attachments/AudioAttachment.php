<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\MediaAttachmentPayload;

final class AudioAttachment extends AbstractAttachment
{
    /**
     * @var MediaAttachmentPayload
     * @readonly
     */
    public $payload;
    /**
     * @var string|null
     * @readonly
     */
    public $transcription;
    /**
     * @param MediaAttachmentPayload $payload Audio attachment payload.
     * @param string|null $transcription Audio transcription.
     */
    public function __construct(
        MediaAttachmentPayload $payload,
        ?string $transcription
    ) {
        $this->payload = $payload;
        $this->transcription = $transcription;
        parent::__construct(AttachmentType::Audio);
    }
}
