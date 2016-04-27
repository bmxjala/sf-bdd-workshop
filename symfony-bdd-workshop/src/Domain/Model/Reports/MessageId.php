<?php

namespace Domain\Model\Reports;

use Domain\EventModel\AggregateId;

class MessageId implements AggregateId
{
    private $messageId;

    public function __construct($messageId)
    {
        $this->messageId = $messageId;
    }

    public static function fromString($string)
    {
        $themeId = new self($string);

        return $themeId;
    }

    public function __toString()
    {
        return $this->messageId;
    }

    public static function generate()
    {
        $random = uniqid();

        return self::fromString($random);
    }
}
