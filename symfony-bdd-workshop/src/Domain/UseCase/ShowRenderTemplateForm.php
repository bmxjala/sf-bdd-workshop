<?php

namespace Domain\UseCase;

use Domain\EventModel\EventBus;
use Domain\EventModel\EventStorage;
use Domain\ReadModel\Projection\TemplateListProjection;
use Domain\ReadModel\Projection\TemplateRenderFormProjection;
use Domain\ReadModel\ProjectionStorage;
use Domain\UseCase\ShowRenderTemplateForm\Command;
use Domain\UseCase\ShowRenderTemplateForm\Responder;

class ShowRenderTemplateForm
{
    /**
     * @var ProjectionStorage
     */
    private $projectionStorage;

    /**
     * @param ProjectionStorage $projectionStorage
     */
    public function __construct(ProjectionStorage $projectionStorage)
    {
        $this->projectionStorage = $projectionStorage;
    }

    public function execute(Command $command, Responder $responder)
    {
        /** @var TemplateRenderFormProjection $projection */
        $projection = $this->projectionStorage->findById('template-render-form', $command->templateId);
        if(empty($projection)) {
            $responder->templateRenderFormNotFound($command->templateId);
            return;
        }

        $responder->templateRenderFormFetchedSuccessfully($projection, $command->type);
    }
}
