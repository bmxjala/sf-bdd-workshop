<?php

namespace Domain\Model\Template;

use Domain\EventModel\AggregateId;

class TemplateVersionId implements AggregateId
{
    private $templateVersionId;

    public function __construct($templateVersionId)
    {
        $this->templateVersionId = $templateVersionId;
    }

    public static function fromString($string)
    {
        $templateVersionId = new self($string);

        return $templateVersionId;
    }

    public function __toString()
    {
        return $this->templateVersionId;
    }

    public static function generate()
    {
        $random = uniqid();

        return self::fromString($random);
    }
}
