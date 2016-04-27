<?php

namespace Domain\UseCase\ListTemplateDraft;

use Domain\ReadModel\Projection\TemplateDraftListProjection;

interface Responder
{
    /**
     * @param $projections TemplateDraftListProjection[]
     */
    public function templateDraftListedSuccessfully(array $projections);
}