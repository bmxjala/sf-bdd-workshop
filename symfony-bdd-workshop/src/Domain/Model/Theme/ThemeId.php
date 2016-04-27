<?php

namespace Domain\Model\Theme;

use Domain\EventModel\AggregateId;

class ThemeId implements AggregateId
{
    private $themeId;

    public function __construct($themeId)
    {
        $this->themeId = $themeId;
    }

    public static function fromString($string)
    {
        $themeId = new self($string);

        return $themeId;
    }

    public function __toString()
    {
        return $this->themeId;
    }

    public static function generate()
    {
        $random = uniqid();

        return self::fromString($random);
    }
}
