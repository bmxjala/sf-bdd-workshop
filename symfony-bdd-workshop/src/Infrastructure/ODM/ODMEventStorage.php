<?php

namespace Infrastructure\ODM;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Domain\EventModel\AggregateHistory;
use Domain\EventModel\AggregateId;
use Domain\EventModel\DomainEvent;
use Domain\EventModel\EventBased;
use Domain\EventModel\EventStorage;
use Infrastructure\ODM\Document\StoredEvent;

class ODMEventStorage implements EventStorage
{
    /**
     * @var DocumentManager
     */
    private $manager;

    /**
     * @var DocumentRepository
     */
    private $repository;

    public function __construct(DocumentManager $manager, ManagerRegistry $managerRegistry)
    {
        $this->manager = $manager;
        $this->repository = $managerRegistry->getRepository('\Infrastructure\ODM\Document\StoredEvent');
    }

    /**
     * @param EventBased $eventBased
     */
    public function add(EventBased $eventBased)
    {
        foreach($eventBased->getEvents() as $event) {
            $storedEvent = new StoredEvent($eventBased->getAggregateId(), get_class($event), $event);
            $this->manager->persist($storedEvent);
        }
        $this->manager->flush();
    }

    /**
     * @param AggregateId $aggregateId
     * @return DomainEvent[]
     */
    public function find(AggregateId $aggregateId)
    {
        $events = [];
        /** @var $storedEvent StoredEvent */
        $storedEvents = $this->repository->findBy(['aggregateId' => (string) $aggregateId]);
        foreach ($storedEvents as $storedEvent) {
            $events[] = $storedEvent->getEvent();
        }

        return $events;
    }

    /**
     * @return DomainEvent[]
     */
    public function getAll()
    {
        $events = [];
        /** @var $storedEvent StoredEvent */
        $storedEvents = $this->repository->findAll();
        foreach ($storedEvents as $storedEvent) {
            $events[] = $storedEvent->getEvent();
        }

        return $events;
    }
}
