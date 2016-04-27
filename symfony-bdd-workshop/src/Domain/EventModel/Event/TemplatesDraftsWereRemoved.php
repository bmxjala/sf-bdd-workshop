<?php

namespace Domain\EventModel\Event;

use Domain\EventModel\DomainEvent;
use Domain\Model\Template\TemplateId;

class TemplatesDraftsWereRemoved implements DomainEvent
{
    private $templateId;

    public function __construct(TemplateId $templateId)
    {
        $this->templateId = $templateId;
    }

    public function getAggregateId()
    {
        return $this->templateId;
    }
}
