<?php

namespace Domain\UseCase\ListThemes;

use Domain\ReadModel\Projection\ThemeListProjection;

interface Responder
{
    /**
     * @param ThemeListProjection[] $projections
     */
    public function themeListedSuccessfully(array $projections);
}
