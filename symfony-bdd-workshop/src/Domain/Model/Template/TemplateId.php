<?php

namespace Domain\Model\Template;

use Domain\EventModel\AggregateId;

class TemplateId implements AggregateId
{
    private $templateId;

    public function __construct($templateId)
    {
        $this->templateId = $templateId;
    }

    public static function fromString($string)
    {
        $templateId = new self($string);

        return $templateId;
    }

    public function __toString()
    {
        return $this->templateId;
    }

    public static function generate()
    {
        $random = uniqid();

        return self::fromString($random);
    }
}
