<?php

namespace BushlanovDev\MaxMessengerBot\Models;

use BushlanovDev\MaxMessengerBot\Models\Updates\AbstractUpdate;

/**
 * List of all updates in chats your bot participated in.
 */
final class UpdateList extends AbstractModel
{
    /**
     * @var AbstractUpdate[]
     * @readonly
     */
    public $updates;
    /**
     * @var int|null
     * @readonly
     */
    public $marker;
    /**
     * @param AbstractUpdate[] $updates Page of updates.
     * @param int|null $marker Pointer to the next data page.
     */
    public function __construct(array $updates, $marker)
    {
        $this->updates = $updates;
        $this->marker = $marker;
    }

    /**
     * Overridden to prevent incorrect usage.
     * UpdateList contains polymorphic objects and must be created via ModelFactory.
     *
     * @param array<string, mixed> $data
     * @throws \LogicException Always.
     * @return static
     */
    public static function fromArray($data)
    {
        throw new \LogicException(
            'Cannot create UpdateList directly from an array. Use ModelFactory::createUpdateList() instead.'
        );
    }
}
