<?php

namespace Domain\UseCase;

use Domain\EventModel\EventBus;
use Domain\EventModel\EventStorage;
use Domain\Model\Mailing\Message;
use Domain\UseCase\ComposeMessage\Command;
use Domain\UseCase\ComposeMessage\Responder;

class ComposeMessage
{
    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @var EventStorage
     */
    private $eventStorage;

    /**
     * @param EventBus $eventBus
     * @param EventStorage $eventStorage
     */
    public function __construct(EventBus $eventBus, EventStorage $eventStorage)
    {
        $this->eventBus = $eventBus;
        $this->eventStorage = $eventStorage;
    }

    /**
     * @param Command $command
     * @param Responder $responder
     */
    public function execute(Command $command, Responder $responder)
    {
        $html = $command->template->getCurrentVersion()->renderHtml($command->template->getTheme(), $command->fields);
        $text = $command->template->getCurrentVersion()->renderPlaintext($command->fields);

        $message = new Message(
            $command->recipient,
            $command->sender,
            $command->subject,
            $html,
            $text
        );

        $command->template->recordComposedMessage($message);
        $this->eventBus->dispatch($command->template->getEvents());
        $this->eventStorage->add($command->template);

        $responder->messageSuccessfullyComposed($message);
    }
}
