<?php

namespace Domain\UseCase\ShowRenderTemplateForm;

use Domain\Model\Template\TemplateId;

class Command
{
    public $templateId;
    public $type;

    public function __construct(TemplateId $templateId, $type = null)
    {
        $this->templateId = $templateId;
        $this->type = $type;
    }
}
