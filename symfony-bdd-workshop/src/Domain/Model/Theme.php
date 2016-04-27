<?php

namespace Domain\Model;

use Domain\EventModel\Event\ThemeWasCreated;
use Domain\EventModel\AggregateHistory;
use Domain\EventModel\EventBased;
use Domain\EventModel\EventSourced;
use Domain\Model\Theme\ThemeId;

class Theme implements EventBased
{
    use EventSourced;

    /**
     * @var ThemeId
     */
    private $themeId;

    /**
     * @var boolean
     */
    private $isDefault;

    /**
     * @var string
     */
    private $name;

    /**
     * @var $content
     */
    private $content;

    /**
     * @param ThemeId $themeId
     */
    public function __construct(ThemeId $themeId)
    {
        $this->themeId = $themeId;
    }

    /**
     * @return ThemeId
     */
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
     * @param string $name
     */
    private function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param boolean $isDefault
     */
    private function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    private function setContent($content)
    {
        $this->content = $content;
    }

    private function applyThemeWasCreated(ThemeWasCreated $event)
    {
        $this->setName($event->getName());
        $this->setContent($event->getContent());
        $this->setIsDefault($event->getIsDefault());

        return $this;
    }

    /**
     * @param string $name
     * @param string $content
     * @param bool $isDefault
     * @return Theme
     */
    public static function create($name, $content, $isDefault = false)
    {
        $theme = new self(ThemeId::generate());
        $theme->name = $name;
        $theme->content = $content;
        $theme->isDefault = $isDefault;
        $theme->recordThat(new ThemeWasCreated($theme->getAggregateId(), $name, $content, $isDefault));

        return $theme;
    }

    /**
     * @param AggregateHistory $aggregateHistory
     *
     * @return Theme
     */
    public static function reconstituteFrom(AggregateHistory $aggregateHistory)
    {
        $themeId = $aggregateHistory->getAggregateId();
        $theme = new self($themeId);
        $events = $aggregateHistory->getEvents();

        foreach ($events as $event) {
            $applyMethod = explode('\\', get_class($event));
            $applyMethod = 'apply' . end($applyMethod);
            $theme->$applyMethod($event);
        }

        return $theme;
    }
}
