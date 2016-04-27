<?php

namespace Domain\ReadModel\Populator;

use Domain\ReadModel\ProjectionPopulator;

class DefaultThemePopulator extends ProjectionPopulator
{
    const PROJECTION_NAME = 'default-theme';

    public function getProjectionName()
    {
        return self::PROJECTION_NAME;
    }
}
