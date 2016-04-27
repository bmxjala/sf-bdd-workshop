<?php

namespace Domain\ReadModel\Listener;

use Domain\EventModel\Event\ThemeWasCreated;
use Domain\ReadModel\AbstractDomainEventListener;
use Domain\ReadModel\DomainEventListener;
use Domain\ReadModel\Projection\ThemeListProjection;

class ThemeListListener extends AbstractDomainEventListener implements DomainEventListener
{
    public function onThemeWasCreated(ThemeWasCreated $event)
    {
        $themeListProjection = new ThemeListProjection(
            $event->getAggregateId(),
            $event->getName(),
            $event->getIsDefault()
        );

        $this->projectionStorage->save($themeListProjection);
    }
}
