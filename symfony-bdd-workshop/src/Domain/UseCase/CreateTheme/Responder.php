<?php

namespace Domain\UseCase\CreateTheme;

use Domain\Model\Theme;

interface Responder
{
    /**
     * @param Theme $theme
     */
    public function themeSuccessfullyCreated(Theme $theme);
}
