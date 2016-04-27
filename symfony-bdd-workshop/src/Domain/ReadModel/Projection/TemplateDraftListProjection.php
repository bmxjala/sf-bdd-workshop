<?php

namespace Domain\ReadModel\Projection;

use Domain\Model\Template\TemplateId;
use Domain\ReadModel\Projection;

class TemplateDraftListProjection implements Projection
{
    const PROJECTION_NAME = 'template-draft-list';

    /**
     * @var TemplateId
     */
    private $templateId;

    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $author;

    /**
     * @param TemplateId $templateId
     * @param $name
     * @param $author
     */
    public function __construct(TemplateId $templateId, $name, $author)
    {
        $this->templateId = $templateId;
        $this->name = $name;
        $this->author = $author;
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
