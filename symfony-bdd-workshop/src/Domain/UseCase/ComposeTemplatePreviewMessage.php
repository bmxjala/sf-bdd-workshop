<?php

namespace Domain\UseCase;

use Domain\Model\Mailing\EmailAddress;
use Domain\Model\Mailing\Message;
use Domain\Model\Mailing\Sender;
use Domain\UseCase\ComposeTemplatePreviewMessage\Command;
use Domain\UseCase\ComposeTemplatePreviewMessage\Responder;

class ComposeTemplatePreviewMessage
{
    /**
     * @param Command $command
     * @param Responder $responder
     */
    public function execute(Command $command, Responder $responder)
    {
        $sender = new Sender(new EmailAddress('mail-service@lendo-dev.se'));
        $sender->setFullName('Lendo Mail Service');

        $html = $command->template->getCurrentVersion()->renderHtml($command->template->getTheme(), $command->fields);
        $text = $command->template->getCurrentVersion()->renderPlaintext($command->fields);

        $message = new Message(
            $command->recipient,
            $sender,
            'Template preview you requested',
            $html,
            $text
        );

        $responder->mailSuccessfullyComposed($message);
    }
}
