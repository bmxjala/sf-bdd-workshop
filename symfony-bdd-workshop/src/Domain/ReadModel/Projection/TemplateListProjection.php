<?php

namespace Domain\ReadModel\Projection;

use Domain\Model\Template\TemplateId;
use Domain\Model\Theme;
use Domain\Model\User\UserId;
use Domain\ReadModel\Projection;

class TemplateListProjection implements Projection
{
    const PROJECTION_NAME = 'template-list';

    /**
     * @var TemplateId
     */
    private $templateId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $themeName;

    /**
     * @param TemplateId $templateId
     * @param string $name
     * @param string $author
     * @param string $themeName
     */
    public function __construct(TemplateId $templateId, $name, $author, $themeName = null)
    {
        $this->templateId = $templateId;
        $this->name = $name;
        $this->author = $author;
        $this->themeName = $themeName;
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getThemeName()
    {
        return $this->themeName;
    }

}
