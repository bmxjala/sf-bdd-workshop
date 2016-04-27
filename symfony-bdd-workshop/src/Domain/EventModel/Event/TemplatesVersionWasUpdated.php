<?php

namespace Domain\EventModel\Event;

use Domain\EventModel\DomainEvent;
use Domain\Model\Template\TemplateId;
use Domain\Model\TemplateVersion;

class TemplatesVersionWasUpdated implements DomainEvent
{
    private $templateId;

    private $currentVersion;

    /**
     * @param $templateId
     * @param $currentVersion
     */
    public function __construct(TemplateId $templateId, TemplateVersion $templateVersion)
    {
        $this->templateId = $templateId;
        $this->currentVersion = $templateVersion;
    }

    public function getAggregateId()
    {
        return $this->templateId;
    }

    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }
}
