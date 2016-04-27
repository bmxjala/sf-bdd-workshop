<?php

namespace Domain\EventModel\Event;

use Domain\EventModel\DomainEvent;
use Domain\Model\Template\TemplateVersionId;

class TemplateVersionWasCreated implements DomainEvent
{
    private $templateVersionId;

    public function __construct(TemplateVersionId $templateVersionId)
    {
        $this->templateVersionId = $templateVersionId;
    }

    public function getAggregateId()
    {
        return $this->templateVersionId;
    }
}
