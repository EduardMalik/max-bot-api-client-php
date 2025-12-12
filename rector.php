<?php

use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) {
    $rectorConfig->sets([
        DowngradeLevelSetList::DOWN_TO_PHP_56,
    ]);
    $rectorConfig->paths([__DIR__ . '/src']);
};
