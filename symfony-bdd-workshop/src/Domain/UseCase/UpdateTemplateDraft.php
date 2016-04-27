<?php

namespace Domain\UseCase;

use Domain\EventModel\EventBus;
use Domain\EventModel\AggregateHistory\TemplateAggregateHistory;
use Domain\EventModel\EventStorage;
use Domain\Model\Template;
use Domain\Model\TemplateDraft;
use Domain\UseCase\UpdateTemplateDraft\Command;
use Domain\UseCase\UpdateTemplateDraft\Responder;

class UpdateTemplateDraft
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
        $templateAggregateHistory = new TemplateAggregateHistory($command->templateId, $this->eventStorage);
        $template = Template::reconstituteFrom($templateAggregateHistory);

        $templateDraft = TemplateDraft::create($command->templateId, $command->ownerId);
        $templateDraft->setName($command->name);
        $templateDraft->setPlaintextContent($command->plaintextContent);
        $templateDraft->setHtmlContent($command->htmlContent);

        $template->updateDraft($templateDraft);

        $this->eventBus->dispatch($templateDraft->getEvents());
        $this->eventStorage->add($templateDraft);
        $this->eventBus->dispatch($template->getEvents());
        $this->eventStorage->add($template);

        $responder->templateDraftSuccessfullyUpdated($template);
    }
}
