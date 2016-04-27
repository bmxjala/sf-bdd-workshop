<?php

namespace Domain\Exception;

class MissingTemplateFieldsException extends \Exception
{
    public function __construct(array $fields)
    {
        $this->message = "Missing fields needed to render message: " . implode(', ', $fields);
    }
}
