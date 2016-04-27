<?php

namespace Domain\ReadModel\Listener;

use Domain\EventModel\Event\TemplatesThemeWasUpdated;
use Domain\EventModel\Event\TemplatesVersionWasUpdated;
use Domain\ReadModel\AbstractDomainEventListener;
use Domain\ReadModel\DomainEventListener;
use Domain\ReadModel\Projection\TemplateListProjection;

class TemplateListListener extends AbstractDomainEventListener implements DomainEventListener
{
    public function onTemplatesVersionWasUpdated(TemplatesVersionWasUpdated $event)
    {
        $templateVersion = $event->getCurrentVersion();

        $templateListProjection = new TemplateListProjection(
            $event->getAggregateId(),
            $templateVersion->getName(),
            $templateVersion->getAuthor()
        );

        $this->projectionStorage->save($templateListProjection);
    }

    public function onTemplatesThemeWasUpdated(TemplatesThemeWasUpdated $event)
    {
        $templateId = $event->getAggregateId();
        $template = $event->getTemplate();
        $templateVersion = $template->getCurrentVersion();
        $themeName = $event->getTheme()->getName();

        $templateListProjection = new TemplateListProjection(
            $templateId,
            $templateVersion->getName(),
            $templateVersion->getAuthor(),
            $themeName
        );

        $this->projectionStorage->save($templateListProjection);
    }
}
