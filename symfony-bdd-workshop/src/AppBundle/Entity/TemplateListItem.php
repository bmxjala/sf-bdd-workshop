<?php

namespace AppBundle\Entity;

use Domain\ReadModel\Projection;

class TemplateListItem implements FromProjection
{
    protected $templateId;
    protected $name;

    function __construct($templateId, $name)
    {
        $this->templateId = $templateId;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTemplateId()
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
     * @param Projection\TemplateListProjection $projection
     * @return TemplateListItem
     */
    public static function createFromProjection(Projection $projection)
    {
        return new self(
            (string) $projection->getAggregateId(),
            $projection->getName()
        );
    }
}
