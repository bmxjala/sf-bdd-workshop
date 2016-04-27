<?php

namespace Domain\Model\User;

class UserId
{
    public $id;

    public function __construct($definedUserId = '')
    {
        $this->id = $definedUserId;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
