<?php

namespace Domain\UseCase;

use Domain\EventModel\EventBus;
use Domain\EventModel\EventStorage;
use Domain\ReadModel\Projection\SendingReportsProjection;
use Domain\ReadModel\ProjectionStorage;
use Domain\UseCase\ShowSendingReports\Command;
use Domain\UseCase\ShowSendingReports\Responder;

class ShowSendingReports
{
    /**
     * @var ProjectionStorage
     */
    private $projectionStorage;

    /**
     * @var SendingReportsProjection[]
     */
    private $projections;

    /**
     * @param ProjectionStorage $projectionStorage
     */
    public function __construct(ProjectionStorage $projectionStorage)
    {
        $this->projectionStorage = $projectionStorage;
        $this->projections = [];
    }

    /**
     * @param Command $command
     * @param Responder $responder
     */
    public function execute(Command $command, Responder $responder)
    {
        if(!empty($command->getMessageId())) {
            $this->projections = [$this->projectionStorage->findById('sending-reports', $command->getMessageId())];
        } else {
            $this->projections = $this->projectionStorage->find('sending-reports');

            $this->narrowResultsByDates($command->getDateFrom(), $command->getDateTo());
            $this->filterResultsBySearch($command->getFilterBy(), $command->getSearchString());
            $this->sortResults($command->getSortBy(), $command->getSortOrder());

        }

        $responder->sendingReportsFetchedSuccessfully($this->projections);
    }

    private function narrowResultsByDates($dateFrom, $dateTo)
    {
        if(empty($dateFrom) && empty($dateTo)) {
            return;
        }

        foreach($this->projections as $key => $projection) {
            if((!empty($dateFrom) && $projection->getDate() < $dateFrom) || (!empty($dateTo) && $projection->getDate() > $dateTo)) {
                unset($this->projections[$key]);
            }
        }
    }

    private function filterResultsBySearch($filterBy, $searchString)
    {
        if(empty($filterBy) || empty($searchString)) {
            return;
        }

        foreach($this->projections as $key => $projection) {
            $getterMethodName = 'get' . ucfirst($filterBy);
            if($filterBy == 'subject') {
                if (!preg_match('/'.$searchString.'/i', $projection->$getterMethodName())) {
                    unset($this->projections[$key]);
                }
            } else {
                if (strtolower($projection->$getterMethodName()) != strtolower($searchString)) {
                    unset($this->projections[$key]);
                }
            }
        }
    }

    private function sortResults($sortBy, $sortOrder = 'asc')
    {
        if(empty($sortBy)) {
            return;
        }

        if($sortOrder == 'desc') {
            $getterMethodName = 'get' . ucfirst($sortBy);
            uasort($this->projections, function ($a, $b) use ($getterMethodName) {
                return ($a->$getterMethodName() > $b->$getterMethodName()) ? -1 : 1;
            });
        } else {
            $getterMethodName = 'get' . ucfirst($sortBy);
            uasort($this->projections, function ($a, $b) use ($getterMethodName) {
                return ($a->$getterMethodName() < $b->$getterMethodName()) ? -1 : 1;
            });
        }
    }
}
