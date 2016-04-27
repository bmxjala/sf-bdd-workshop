<?php

namespace Domain\ReadModel\Listener;

use Domain\EventModel\Event\TemplatesVersionWasUpdated;
use Domain\ReadModel\AbstractDomainEventListener;
use Domain\ReadModel\DomainEventListener;
use Domain\ReadModel\Projection\TemplateRenderFormProjection;

class TemplateRenderFormListener extends AbstractDomainEventListener implements DomainEventListener
{
    public function onTemplatesVersionWasUpdated(TemplatesVersionWasUpdated $event)
    {
        $templateVersion = $event->getCurrentVersion();

        $plaintextFields = $templateVersion->extractFromPlaintext();
        $htmlFields = $templateVersion->extractFromHtml();

        $templateRenderFormProjection = new TemplateRenderFormProjection($event->getAggregateId(), $plaintextFields, $htmlFields);
        $this->projectionStorage->save($templateRenderFormProjection);
    }
}
