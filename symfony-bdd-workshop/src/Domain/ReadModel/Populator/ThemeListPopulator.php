<?php

namespace Domain\ReadModel\Populator;

use Domain\ReadModel\ProjectionPopulator;

class ThemeListPopulator extends ProjectionPopulator
{
    const PROJECTION_NAME = 'theme-list';

    public function getProjectionName()
    {
        return self::PROJECTION_NAME;
    }
}
