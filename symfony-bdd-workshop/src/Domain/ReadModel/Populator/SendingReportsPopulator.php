<?php

namespace Domain\ReadModel\Populator;

use Domain\ReadModel\ProjectionPopulator;

class SendingReportsPopulator extends ProjectionPopulator
{
    const PROJECTION_NAME = 'sending-reports';

    public function getProjectionName()
    {
        return self::PROJECTION_NAME;
    }
}
