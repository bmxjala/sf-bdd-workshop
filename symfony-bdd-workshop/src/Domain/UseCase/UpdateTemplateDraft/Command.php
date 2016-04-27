<?php

namespace Domain\UseCase\UpdateTemplateDraft;

use Domain\Model\Template\TemplateId;
use Domain\Model\User\UserId;

class Command
{
    public $name;
    public $plaintextContent;
    public $htmlContent;
    public $templateDraftId;
    public $templateId;
    public $ownerId;

    public function __construct(
        TemplateId $templateId,
        UserId $ownerId,
        $name,
        $plaintextContent,
        $htmlContent
    ) {
        $this->name = $name;
        $this->plaintextContent = $plaintextContent;
        $this->htmlContent = $htmlContent;
        $this->templateId = $templateId;
        $this->ownerId = $ownerId;
    }
}
