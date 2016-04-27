<?php

namespace Domain\ReadModel\Listener;

use Domain\EventModel\Event\ThemeWasCreated;
use Domain\ReadModel\AbstractDomainEventListener;
use Domain\ReadModel\DomainEventListener;
use Domain\ReadModel\Projection\DefaultThemeProjection;

class DefaultThemeListener extends AbstractDomainEventListener implements DomainEventListener
{
    public function onThemeWasCreated(ThemeWasCreated $event)
    {
        if (!$event->getIsDefault()) {
            return;
        }

        $existingDefaultThemeProjections = $this->projectionStorage->find('default-theme');
        if(!empty($existingDefaultThemeProjections)) {
            $existingDefaultThemeProjection = reset($existingDefaultThemeProjections);
            $this->projectionStorage->remove($existingDefaultThemeProjection);
        }

        $newDefaultThemeProjection = new DefaultThemeProjection($event->getAggregateId());
        $this->projectionStorage->save($newDefaultThemeProjection);
    }
}
