<?php

namespace Domain\EventModel\Event;

use Domain\EventModel\DomainEvent;
use Domain\Model\Template\TemplateDraftId;

class TemplateDraftWasCreated implements DomainEvent
{
    private $templateDraftId;

    public function __construct(TemplateDraftId $templateDraftId)
    {
        $this->templateDraftId = $templateDraftId;
    }

    public function getAggregateId()
    {
        return $this->templateDraftId;
    }
}
