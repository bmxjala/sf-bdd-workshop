<?php

namespace AppBundle\Entity;

use Domain\EventModel\EventBased;
use Domain\Model\Template as DomainTemplate;

class Template implements FromDomainObject
{
    protected $id;

    protected $author;

    protected $name;

    protected $plaintextContent;

    protected $htmlContent;

    protected $renderFields;

    function __construct($id, $author, $name, $plaintextContent, $htmlContent)
    {
        $this->id = $id;
        $this->author = $author;
        $this->name = $name;
        $this->plaintextContent = $plaintextContent;
        $this->htmlContent = $htmlContent;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPlaintextContent()
    {
        return $this->plaintextContent;
    }

    /**
     * @return string
     */
    public function getHtmlContent()
    {
        return $this->htmlContent;
    }

    /**
     * @return array
     */
    public function getRenderFields()
    {
        return $this->renderFields;
    }

    /**
     * @param array $renderFields
     */
    public function setRenderFields(array $renderFields)
    {
        $this->renderFields = $renderFields;
    }

    /**
     * @param DomainTemplate $domainTemplate
     * @return Template
     */
    public static function createFromDomainObject(EventBased $domainTemplate)
    {
        return new self(
            (string) $domainTemplate->getAggregateId(),
            (string) $domainTemplate->getCurrentVersion()->getAuthor(),
            $domainTemplate->getCurrentVersion()->getName(),
            $domainTemplate->getCurrentVersion()->getPlaintextContent(),
            $domainTemplate->getCurrentVersion()->getHtmlContent()
        );
    }
}
