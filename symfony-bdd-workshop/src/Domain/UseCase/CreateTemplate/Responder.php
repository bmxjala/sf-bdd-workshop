<?php

namespace Domain\UseCase\CreateTemplate;

use Domain\Model\Template;
use Domain\Model\TemplateVersion;
use Domain\Model\TemplateDraft;

interface Responder
{
    /**
     * @param Template $template
     * @param TemplateVersion $templateVersion
     * @param TemplateDraft $templateDraft
     */
    public function templateSuccessfullyCreated(Template $template, TemplateVersion $templateVersion, TemplateDraft $templateDraft);

    /**
     * @param \Exception $e
     */
    public function templateCreatingFailed(\Exception $e);
}
