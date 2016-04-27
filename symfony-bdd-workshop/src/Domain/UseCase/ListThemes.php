<?php

namespace Domain\UseCase;

use Domain\ReadModel\ProjectionStorage;
use Domain\UseCase\ListThemes\Command;
use Domain\UseCase\ListThemes\Responder;

class ListThemes
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

    /**
     * @param Command $command
     * @param Responder $responder
     */
    public function execute(Command $command, Responder $responder)
    {
        $projections = $this->projectionStorage->find('theme-list');
        $responder->themeListedSuccessfully($projections);
    }
}
