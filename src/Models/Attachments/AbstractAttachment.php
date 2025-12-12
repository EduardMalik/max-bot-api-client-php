<?php

namespace BushlanovDev\MaxMessengerBot\Models\Attachments;

use BushlanovDev\MaxMessengerBot\Enums\AttachmentType;
use BushlanovDev\MaxMessengerBot\Models\AbstractModel;

/**
 * Represents a generic attachment received from the API.
 */
abstract class AbstractAttachment extends AbstractModel
{
    /**
     * @readonly
     * @var \BushlanovDev\MaxMessengerBot\Enums\AttachmentType
     */
    public $type;
    /**
     * @param mixed $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }
}
