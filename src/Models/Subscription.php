<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Attributes\ArrayOf;
use BushlanovDev\MaxMessengerBot\Enums\UpdateType;

/**
 * Information about webhook subscriptions.
 */
final class Subscription extends AbstractModel
{
    /**
     * @var string
     * @readonly
     */
    public $url;
    /**
     * @var int
     * @readonly
     */
    public $time;
    /**
     * @var UpdateType[]|null
     * @readonly
     */
    public $updateTypes;
    /**
     * @var string|null
     * @readonly
     */
    public $version;
    /**
     * @param string $url URL webhook.
     * @param int $time Unix-time of creating a subscription.
     * @param UpdateType[]|null $updateTypes List of update types.
     * @param string|null $version Version of the API.
     */
    public function __construct(
        string $url,
        int $time,
        #[\BushlanovDev\MaxMessengerBot\Attributes\ArrayOf(\BushlanovDev\MaxMessengerBot\Enums\UpdateType::class)]
        ?array $updateTypes,
        ?string $version
    )
    {
        $this->url = $url;
        $this->time = $time;
        $this->updateTypes = $updateTypes;
        $this->version = $version;
    }
}
