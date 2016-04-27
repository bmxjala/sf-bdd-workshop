<?php

namespace Domain\ReadModel\Projection;

use Domain\Model\Theme\ThemeId;
use Domain\ReadModel\Projection;

class ThemeListProjection implements Projection
{
    const PROJECTION_NAME = 'theme-list';

    /**
     * @var ThemeId
     */
    private $themeId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $isDefault;

    /**
     * @param ThemeId $themeId
     * @param $name
     * @param $isDefault
     */
    public function __construct(ThemeId $themeId, $name, $isDefault)
    {
        $this->themeId = $themeId;
        $this->name = $name;
        $this->isDefault = $isDefault;
    }

    /**
     * @return string
     */
    public function getProjectionName()
    {
        return self::PROJECTION_NAME;
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
     * @return boolean
     */
    public function isDefault()
    {
        return $this->isDefault;
    }
}
