<?php

namespace Domain\ReadModel;

interface Projection 
{
    public function getProjectionName();
    public function getAggregateId();
}
