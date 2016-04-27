<?php

namespace Domain\Exception;

class MissingDefaultThemeException extends \Exception
{
    public function __construct()
    {
        $this->message = "Missing default theme! Looks like application is improperly configured.";
    }
}
