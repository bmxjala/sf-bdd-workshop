<?php

namespace Domain\EventModel\Event;

use Domain\EventModel\DomainEvent;
use Domain\Model\Template;
use Domain\Model\Template\TemplateId;
use Domain\Model\Theme;

class TemplatesThemeWasUpdated implements DomainEvent
{
    private $theme;
    private $templateId;
    private $template;

    public function __construct(Template $template, Theme $theme)
    {
        $this->templateId = $template->getAggregateId();
        $this->template = $template;
        $this->theme = $theme;
    }

    public function getAggregateId()
    {
        return $this->templateId;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getTheme()
    {
        return $this->theme;
    }
}
