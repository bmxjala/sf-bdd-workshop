<?php

namespace Domain\UseCase\ShowRenderTemplateForm;

use Domain\Model\Template\TemplateId;
use Domain\ReadModel\Projection\TemplateRenderFormProjection;

interface Responder
{
    /**
     * @param $projection TemplateRenderFormProjection
     * @param $type 'null|text|html'
     */
    public function templateRenderFormFetchedSuccessfully(TemplateRenderFormProjection $projection, $type = null);

    /**
     * @param $templateId TemplateId
     */
    public function templateRenderFormNotFound(TemplateId $templateId);
}