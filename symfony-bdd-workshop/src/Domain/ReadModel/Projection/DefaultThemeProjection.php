<?php

namespace Domain\ReadModel\Projection;

use Domain\Model\Theme\ThemeId;
use Domain\ReadModel\Projection;

class DefaultThemeProjection implements Projection
{
    const PROJECTION_NAME = 'default-theme';

    /**
     * @var ThemeId
     */
    private $themeId;

    /**
     * @param ThemeId $themeId
     */
    public function __construct(ThemeId $themeId)
    {
        $this->themeId = $themeId;
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
}