<?php

namespace Domain\UseCase\CreateTheme;

class Command
{
    public $name;
    public $content;
    public $isDefault;

    public function __construct($name, $content, $isDefault = false)
    {
        $this->name = $name;
        $this->content = $content;
        $this->isDefault = $isDefault;
    }
}
