<?php

namespace Domain\EventModel;

abstract class AggregateHistory
{
    /**
     * @var $aggregateId AggregateId
     */
    protected $aggregateId;

    /**
     * @var $events DomainEvent[]
     */
    protected $events;

    public function __construct(AggregateId $aggregateId, EventStorage $eventStorage)
    {
        $this->aggregateId = $aggregateId;
        $this->events = $eventStorage->find($aggregateId);
    }

    public function getAggregateId()
    {
        return $this->aggregateId;
    }

    public function getEvents()
    {
        return $this->events;
    }
}
