<?php

namespace Domain\ReadModel\Populator;

use Domain\ReadModel\ProjectionPopulator;

class TemplateRenderFormPopulator extends ProjectionPopulator
{
    const PROJECTION_NAME = 'template-render-form';

    public function getProjectionName()
    {
        return self::PROJECTION_NAME;
    }
}
