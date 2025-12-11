<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use InvalidArgumentException;

/**
 * Request to attach image. All fields are mutually exclusive.
 */
final class PhotoAttachmentRequestPayload extends AbstractAttachmentRequestPayload
{
    /**
     * @var string|null
     * @readonly
     */
    public $url;
    /**
     * @var string|null
     * @readonly
     */
    public $token;
    /**
     * @var PhotoToken[]|null
     * @readonly
     */
    public $photos;
    /**
     * @param string|null $url Any external image URL you want to attach.
     * @param string|null $token Token of any existing attachment.
     * @param PhotoToken[]|null $photos Tokens were obtained after uploading images.
     */
    public function __construct(
        ?string $url = null,
        ?string $token = null,
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\BushlanovDev\MaxMessengerBot\Models\Attachments\Payloads\PhotoToken::class)]
        ?array $photos = null
    ) {
        $this->url = $url;
        $this->token = $token;
        $this->photos = $photos;
        if ($this->url === null && $this->token === null && $this->photos === null) {
            throw new InvalidArgumentException(
                'Provide one of "url", "token", or "photos" for PhotoAttachmentRequestPayload.'
            );
        }
    }
}
