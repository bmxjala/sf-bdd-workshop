<?php

namespace Domain\EventModel\Event;

use Domain\EventModel\DomainEvent;
use Domain\Model\Template\TemplateId;
use Domain\Model\TemplateDraft;

class TemplatesDraftWasUpdated implements DomainEvent
{
    /**
     * @var TemplateId
     */
    private $templateId;

    /**
     * @var TemplateDraft
     */
    private $templateDraft;

    /**
     * @param TemplateId $templateId
     * @param TemplateDraft $templateDraft
     */
    public function __construct(TemplateId $templateId, TemplateDraft $templateDraft)
    {
        $this->templateId = $templateId;
        $this->templateDraft = $templateDraft;
    }

    /**
     * @return TemplateDraft
     */
    public function getTemplateDraft()
    {
        return $this->templateDraft;
    }

    /**
     * @return string
     */
    public function getAggregateId()
    {
        return $this->templateId;
    }
}
