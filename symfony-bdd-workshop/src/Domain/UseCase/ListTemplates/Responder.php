<?php

namespace Domain\UseCase\ListTemplates;

use Domain\ReadModel\Projection\TemplateListProjection;

interface Responder
{
    /**
     * @param $projections TemplateListProjection[]
     */
    public function templateListedSuccessfully(array $projections);
}