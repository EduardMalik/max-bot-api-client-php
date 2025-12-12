<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\FileAttachmentPayload;

final class FileAttachment extends AbstractAttachment
{
    /**
     * @var FileAttachmentPayload
     * @readonly
     */
    public $payload;
    /**
     * @var string
     * @readonly
     */
    public $filename;
    /**
     * @var int
     * @readonly
     */
    public $size;
    /**
     * @param FileAttachmentPayload $payload File attachment payload.
     * @param string $filename Uploaded file name.
     * @param int $size File size in bytes.
     */
    public function __construct(
        FileAttachmentPayload $payload,
        $filename,
        $size
    ) {
        $filename = (string) $filename;
        $size = (int) $size;
        $this->payload = $payload;
        $this->filename = $filename;
        $this->size = $size;
        parent::__construct(AttachmentType::File);
    }
}
