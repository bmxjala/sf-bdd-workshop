<?php

namespace Domain\UseCase\ComposeMessage;

use Domain\Model\Mailing\Message;

interface Responder
{
    /**
     * @param Message $message
     */
    public function messageSuccessfullyComposed(Message $message);
}
