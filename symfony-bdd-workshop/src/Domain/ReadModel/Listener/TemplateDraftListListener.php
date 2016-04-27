<?php

namespace Domain\ReadModel\Listener;

use Domain\EventModel\Event\TemplatesDraftsWereRemoved;
use Domain\EventModel\Event\TemplatesDraftWasUpdated;
use Domain\EventModel\EventBus;
use Domain\EventModel\DomainEvent;
use Domain\ReadModel\AbstractDomainEventListener;
use Domain\ReadModel\DomainEventListener;
use Domain\ReadModel\ProjectionStorage;
use Domain\ReadModel\Projection\TemplateDraftListProjection;

class TemplateDraftListListener extends AbstractDomainEventListener implements DomainEventListener
{
    public function onTemplatesDraftWasUpdated(TemplatesDraftWasUpdated $event)
    {
        $templateDraftListProjection = new TemplateDraftListProjection(
            $event->getAggregateId(),
            $event->getTemplateDraft()->getName(),
            $event->getTemplateDraft()->getOwner()
        );
        $this->projectionStorage->save($templateDraftListProjection);
    }

    public function onTemplatesDraftsWereRemoved(TemplatesDraftsWereRemoved $event)
    {
        $templateDraftListProjection = $this->projectionStorage->findById('template-draft-list', $event->getAggregateId());
        if(!empty($templateDraftListProjection)) {
            $this->projectionStorage->remove($templateDraftListProjection);
        }
    }
}
