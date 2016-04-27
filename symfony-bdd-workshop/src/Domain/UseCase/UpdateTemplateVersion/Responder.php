<?php

namespace Domain\UseCase\UpdateTemplateVersion;

use Domain\Model\Template;
use Domain\Model\TemplateVersion;

interface Responder
{
    /**
     * @param Template $template
     * @param TemplateVersion $templateVersion
     */
    public function templateSuccessfullyUpdated(Template $template, TemplateVersion $templateVersion);

    /**
     * @param \Exception $e
     */
    public function templateUpdateFailed(\Exception $e);
}
