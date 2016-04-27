<?php

namespace Domain\ReadModel;

use Domain\EventModel\DomainEvent;

interface DomainEventListener
{
    public function when(DomainEvent $event);
}
