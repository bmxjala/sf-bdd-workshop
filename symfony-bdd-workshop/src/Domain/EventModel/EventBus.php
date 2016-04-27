<?php

namespace Domain\EventModel;

use Domain\EventModel\DomainEvent;
use Domain\ReadModel\DomainEventListener;

class EventBus
{
    /**
     * @var $listeners DomainEventListener[]
     */
    private $listeners = [];

    /**
     * @param DomainEventListener $domainEventListener
     */
    public function registerListener(DomainEventListener $domainEventListener)
    {
        $this->listeners[] = $domainEventListener;
    }

    /**
     * @param $events DomainEvent[]
     */
    public function dispatch($events)
    {
        foreach ($events as $event) {
            foreach ($this->listeners as $listener) {
                $listener->when($event);
            }
        }

        return $this;
    }
}
