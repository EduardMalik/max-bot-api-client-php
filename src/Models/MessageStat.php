<?php

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Message statistics.
 */
final class MessageStat extends AbstractModel
{
    /**
     * @var int
     * @readonly
     */
    public $views;
    /**
     * @param int $views Number of views.
     */
    public function __construct($views)
    {
        $views = (int) $views;
        $this->views = $views;
    }
}
