<?php

namespace Domain\ReadModel\Projection;

use Domain\Model\Template\TemplateId;
use Domain\ReadModel\Projection;

class TemplateRenderFormProjection implements Projection
{
    const PROJECTION_NAME = 'template-render-form';

    /**
     * @var TemplateId
     */
    private $templateId;

    /**
     * @var array
     */
    private $plaintextFields;

    /**
     * @var array
     */
    private $htmlFields;

    /**
     * @param TemplateId $templateId
     * @param array $plaintextFields
     * @param array $htmlFields
     */
    public function __construct(TemplateId $templateId, $plaintextFields, $htmlFields)
    {
        $this->templateId = $templateId;
        $this->plaintextFields = $plaintextFields;
        $this->htmlFields = $htmlFields;
    }

    /**
     * @return string
     */
    public function getProjectionName()
    {
        return self::PROJECTION_NAME;
    }

    /**
     * @return TemplateId
     */
    public function getAggregateId()
    {
        return $this->templateId;
    }

    /**
     * @return array
     */
    public function getPlaintextFields()
    {
        return $this->plaintextFields;
    }

    /**
     * @return array
     */
    public function getHtmlFields()
    {
        return $this->htmlFields;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return array_values(array_unique(array_merge($this->plaintextFields, $this->htmlFields)));
    }
}
