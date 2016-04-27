<?php

namespace Domain\UseCase\ListTemplateDraft;

use Domain\Model\User\UserId;

class Command
{
    public $owner;

    public function __construct(UserId $owner)
    {
        $this->owner = $owner;
    }
}
