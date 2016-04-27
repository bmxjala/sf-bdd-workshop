<?php

namespace Domain\UseCase\ShowSendingReports;

use Domain\ReadModel\Projection\SendingReportsProjection;

interface Responder
{
    /**
     * @param $projections SendingReportsProjection[]
     */
    public function sendingReportsFetchedSuccessfully(array $projections);
}