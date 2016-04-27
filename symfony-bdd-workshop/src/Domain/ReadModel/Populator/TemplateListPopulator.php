<?php

namespace Domain\ReadModel\Populator;

use Domain\ReadModel\ProjectionPopulator;

class TemplateListPopulator extends ProjectionPopulator
{
    const PROJECTION_NAME = 'template-list';

    public function getProjectionName()
    {
        return self::PROJECTION_NAME;
    }
}
