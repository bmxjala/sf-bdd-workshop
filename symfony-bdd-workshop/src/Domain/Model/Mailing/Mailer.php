<?php

namespace Domain\Model\Mailing;

interface Mailer
{
    /**
     * @param Message $message
     * @return bool
     */
    public function send(Message $message);
}
