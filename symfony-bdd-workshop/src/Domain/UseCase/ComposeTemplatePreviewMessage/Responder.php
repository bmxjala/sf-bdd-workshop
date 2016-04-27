<?php

namespace Domain\UseCase\ComposeTemplatePreviewMessage;

use Domain\Model\Mailing\Message;

interface Responder
{
    /**
     * @param Message $message
     */
    public function mailSuccessfullyComposed(Message $message);
}
