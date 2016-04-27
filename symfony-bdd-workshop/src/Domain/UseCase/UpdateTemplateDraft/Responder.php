<?php

namespace Domain\UseCase\UpdateTemplateDraft;

use Domain\Model\Template;

interface Responder
{
    /**
     * @param Template $template
     */
    public function templateDraftSuccessfullyUpdated(Template $template);
}
