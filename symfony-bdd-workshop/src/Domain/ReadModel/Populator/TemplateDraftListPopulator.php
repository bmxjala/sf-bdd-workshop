<?php

namespace Domain\ReadModel\Populator;

use Domain\ReadModel\ProjectionPopulator;

class TemplateDraftListPopulator extends ProjectionPopulator
{
    const PROJECTION_NAME = 'template-draft-list';

    public function getProjectionName()
    {
        return self::PROJECTION_NAME;
    }
}
