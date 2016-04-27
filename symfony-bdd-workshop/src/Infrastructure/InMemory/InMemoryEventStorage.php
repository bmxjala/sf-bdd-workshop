<?php

namespace Infrastructure\InMemory;

use Domain\EventModel\AggregateHistory;
use Domain\EventModel\AggregateId;
use Domain\EventModel\DomainEvent;
use Domain\EventModel\EventBased;
use Domain\EventModel\EventStorage;
use Everzet\PersistedObjects\AccessorObjectIdentifier;
use Everzet\PersistedObjects\InMemoryRepository;

class InMemoryEventStorage implements EventStorage
{
    private $repo;

    public function __construct()
    {
        $this->repo = new InMemoryRepository(new AccessorObjectIdentifier('getAggregateId'));
    }

    /**
     * @param EventBased $eventBased
     */
    public function add(EventBased $eventBased)
    {
        $this->repo->save($eventBased);
    }

    /**
     * @param AggregateId $aggregateId
     * @return DomainEvent[]
     */
    public function find(AggregateId $aggregateId)
    {
        $events = [];
        foreach($this->repo->getAll() as $storedEvent) {
            if($storedEvent->getAggregateId() == $aggregateId) {
                $events = array_merge($events, $storedEvent->getEvents());
            }
        }

        return $events;
    }

    /**
     * @return DomainEvent[]
     */
    public function getAll()
    {
        $events = [];
        foreach($this->repo->getAll() as $storedEvent) {
            $events = array_merge($events, $storedEvent->getEvents());
        }

        return $events;
    }
}