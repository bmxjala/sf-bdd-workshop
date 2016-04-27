<?php

namespace Infrastructure\InMemory;

use Domain\EventModel\AggregateId;
use Domain\ReadModel\Projection;
use Domain\ReadModel\ProjectionStorage;
use Everzet\PersistedObjects\AccessorObjectIdentifier;
use Everzet\PersistedObjects\InMemoryRepository;
use Infrastructure\InMemory\Document\StoredProjection;

class InMemoryProjectionStorage implements ProjectionStorage
{
    private $repo;

    public function __construct()
    {
        $this->repo = new InMemoryRepository(new AccessorObjectIdentifier('getId'));
    }

    /**
     * @param Projection $projection
     */
    public function save(Projection $projection)
    {
        $storedProjection = new StoredProjection($projection->getProjectionName(), $projection->getAggregateId(), $projection);
        $this->repo->save($storedProjection);
    }

    /**
     * @param Projection $projection
     */
    public function remove(Projection $projection)
    {
        $storedProjection = $this->repo->findById($projection->getProjectionName() . '_' . $projection->getAggregateId());
        $this->repo->remove($storedProjection);
    }

    /**
     * @param $projectionName
     * @return Projection[]
     */
    public function find($projectionName)
    {
        $projections = [];
        /** @var $storedProjection StoredProjection */
        foreach($this->repo->getAll() as $storedProjection) {
            if($storedProjection->getProjectionName() == $projectionName) {
                $projections[] = $storedProjection->getProjection();
            }
        }

        return $projections;
    }

    /**
     * @param $projectionName
     * @param AggregateId $aggregateId
     * @return Projection
     */
    public function findById($projectionName, AggregateId $aggregateId)
    {
        $projections = [];
        /** @var $storedProjection StoredProjection */
        foreach($this->repo->getAll() as $storedProjection) {
            if($storedProjection->getProjectionName() == $projectionName && $storedProjection->getAggregateId() == $aggregateId) {
                return $storedProjection->getProjection();
            }
        }

        return null;
    }
}
