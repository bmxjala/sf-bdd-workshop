<?php

namespace Domain\UseCase;

use Domain\EventModel\EventBus;
use Domain\EventModel\EventStorage;
use Domain\Model\Theme;
use Domain\UseCase\CreateTheme\Command;
use Domain\UseCase\CreateTheme\Responder;

class CreateTheme
{
    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @var EventStorage
     */
    private $eventStorage;

    /**
     * @param EventBus $eventBus
     * @param EventStorage $eventStorage
     */
    public function __construct(EventBus $eventBus, EventStorage $eventStorage)
    {
        $this->eventBus = $eventBus;
        $this->eventStorage = $eventStorage;
    }

    public function execute(Command $command, Responder $responder)
    {
        $theme = Theme::create(
            $command->name,
            $command->content,
            $command->isDefault
        );

        $this->eventBus->dispatch($theme->getEvents());
        $this->eventStorage->add($theme);

        $responder->themeSuccessfullyCreated($theme);
    }
}
