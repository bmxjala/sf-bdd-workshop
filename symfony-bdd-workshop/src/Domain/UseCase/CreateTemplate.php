<?php

namespace Domain\UseCase;

use Domain\EventModel\EventBus;
use Domain\EventModel\EventStorage;
use Domain\UseCase\CreateTemplate\Command;
use Domain\UseCase\CreateTemplate\Responder;
use Domain\Model\Template;
use Domain\Model\TemplateVersion;
use Domain\Model\TemplateDraft;

class CreateTemplate
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

    public function execute(Command $command, Responder $responder)
    {
        $template = Template::create();
        try {
            $this->eventBus->dispatch($template->getEvents());
            $this->eventStorage->add($template);
        } catch (\Exception $e) {
            $responder->templateCreatingFailed($e);
        }

        $templateDraft = TemplateDraft::create($template->getAggregateId(), $command->author);
        try {
            $this->eventBus->dispatch($templateDraft->getEvents());
            $this->eventStorage->add($templateDraft);
        } catch (\Exception $e) {
            $responder->templateCreatingFailed($e);
        }

        $templateVersion = TemplateVersion::create($command->author);
        try {
            $this->eventBus->dispatch($templateVersion->getEvents());
            $this->eventStorage->add($templateVersion);
        } catch (\Exception $e) {
            $responder->templateCreatingFailed($e);
        }

        $template->updateVersion($templateVersion);
        $template->updateDraft($templateDraft);

        $responder->templateSuccessfullyCreated($template, $templateVersion, $templateDraft);
    }
}
