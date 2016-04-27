<?php

namespace Domain\Model\Template;

use Domain\EventModel\AggregateId;

class TemplateDraftId implements AggregateId
{
    private $templateDraftId;

    public function __construct($templateDraftId)
    {
        $this->templateDraftId = $templateDraftId;
    }

    public static function fromString($string)
    {
        $templateDraftId = new self($string);

        return $templateDraftId;
    }

    public function __toString()
    {
        return $this->templateDraftId;
    }

    public static function generate()
    {
        $random = uniqid();

        return self::fromString($random);
    }
}
