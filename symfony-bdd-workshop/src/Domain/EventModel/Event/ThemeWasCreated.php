<?php

namespace Domain\EventModel\Event;

use Domain\EventModel\DomainEvent;
use Domain\Model\Theme\ThemeId;

class ThemeWasCreated implements DomainEvent
{
    private $themeId;

    private $name;

    private $content;

    private $isDefault;

    public function __construct(ThemeId $themeId, $name, $content, $isDefault)
    {
        $this->themeId = $themeId;
        $this->name = $name;
        $this->content = $content;
        $this->isDefault = $isDefault;
    }

    public function getAggregateId()
    {
        return $this->themeId;
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

}
