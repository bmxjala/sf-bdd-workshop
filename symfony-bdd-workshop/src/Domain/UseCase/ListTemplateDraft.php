<?php

namespace Domain\UseCase;

use Domain\EventModel\EventBus;
use Domain\EventModel\EventStorage;
use Domain\ReadModel\ProjectionStorage;
use Domain\UseCase\ListTemplateDraft\Command;
use Domain\UseCase\ListTemplateDraft\Responder;

class ListTemplateDraft
{
    /**
     * @var ProjectionStorage
     */
    private $projectionStorage;

    /**
     * @param EventBus $eventBus
     * @param EventStorage $eventStorage
     */
    public function __construct(ProjectionStorage $projectionStorage)
    {
        $this->projectionStorage = $projectionStorage;
    }

    public function execute(Command $command, Responder $responder)
    {
        $allProjections = $this->projectionStorage->find('template-draft-list');
        $projections = $allProjections; // @todo: filter by command->owner here

        $responder->templateDraftListedSuccessfully($projections);
    }
}
