<?php

namespace Domain\UseCase\CreateTemplate;

use Domain\Model\User\UserId;

class Command
{
    public $author;

    public function __construct(UserId $author)
    {
        $this->author = $author;
    }
}
