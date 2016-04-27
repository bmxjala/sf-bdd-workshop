<?php

namespace Domain\UseCase;

use Domain\EventModel\EventBus;
use Domain\EventModel\EventStorage;
use Domain\ReadModel\ProjectionStorage;
use Domain\UseCase\ListTemplates\Command;
use Domain\UseCase\ListTemplates\Responder;

class ListTemplates
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
        $projections = $this->projectionStorage->find('template-list');
        $responder->templateListedSuccessfully($projections);
    }
}
