<?php

namespace Domain\EventModel\Event;

use Domain\EventModel\DomainEvent;
use Domain\Model\Template\TemplateVersionId;

class TemplateVersionWasUpdated implements DomainEvent
{
    /**
     * @var TemplateVersionId
     */
    private $templateVersionId;

    private $name;

    private $plaintextContent;

    private $htmlContent;

    /**
     * @param TemplateVersionId $templateVersionId
     * @param $name
     * @param $plaintextContent
     * @param $htmlContent
     */
    public function __construct(TemplateVersionId $templateVersionId, $name, $plaintextContent, $htmlContent)
    {
        $this->templateVersionId = $templateVersionId;
        $this->name = $name;
        $this->plaintextContent = $plaintextContent;
        $this->htmlContent = $htmlContent;
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
    public function getPlaintextContent()
    {
        return $this->plaintextContent;
    }

    /**
     * @return mixed
     */
    public function getHtmlContent()
    {
        return $this->htmlContent;
    }

    public function getAggregateId()
    {
        return $this->templateVersionId;
    }
}
