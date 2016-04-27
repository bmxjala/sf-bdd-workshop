<?php

namespace Domain\ReadModel;

use Domain\EventModel\EventBus;
use Domain\EventModel\DomainEvent;

abstract class AbstractDomainEventListener implements DomainEventListener
{
    protected $projectionStorage;

    public function __construct(EventBus $eventBus, ProjectionStorage $projectionStorage)
    {
        $eventBus->registerListener($this);
        $this->projectionStorage = $projectionStorage;
    }

    public function when(DomainEvent $event)
    {
        $method = explode('\\', get_class($event));
        $method = 'on' . end($method);
        if (method_exists($this, $method)) {
            $this->$method($event);
        }
    }
}
