<?php

namespace Domain\UseCase;

use Domain\EventModel\EventBus;
use Domain\EventModel\AggregateHistory\TemplateAggregateHistory;
use Domain\EventModel\AggregateHistory\TemplateVersionAggregateHistory;
use Domain\EventModel\AggregateHistory\ThemeAggregateHistory;
use Domain\EventModel\EventStorage;
use Domain\Exception\MissingDefaultThemeException;
use Domain\Model\Template;
use Domain\Model\TemplateVersion;
use Domain\Model\Theme;
use Domain\ReadModel\Projection\DefaultThemeProjection;
use Domain\ReadModel\ProjectionStorage;
use Domain\UseCase\UpdateTemplateVersion\Command;
use Domain\UseCase\UpdateTemplateVersion\Responder;

class UpdateTemplateVersion
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
        $templateAggregateHistory = new TemplateAggregateHistory($command->templateId, $this->eventStorage);
        $template = Template::reconstituteFrom($templateAggregateHistory);

        $themeAggregateHistory = new ThemeAggregateHistory($command->themeId, $this->eventStorage);
        $theme = Theme::reconstituteFrom($themeAggregateHistory);

        $templateVersionAggregateHistory = new TemplateVersionAggregateHistory($command->templateVersionId, $this->eventStorage);
        $templateVersion = TemplateVersion::reconstituteFrom($templateVersionAggregateHistory, $command->userId);
        $templateVersion->update($command->name, $command->plaintextContent, $command->htmlContent);
        try {
            $this->eventBus->dispatch($templateVersion->getEvents());
            $this->eventStorage->add($templateVersion);
        } catch(\Exception $e) {
            $responder->templateUpdateFailed($e);
        }

        $template->updateVersion($templateVersion);
        try {
            $this->eventBus->dispatch($template->getEvents());
            $this->eventStorage->add($template);
        } catch(\Exception $e) {
            $responder->templateUpdateFailed($e);
        }

        $template->updateTheme($theme);
        try {
            $this->eventBus->dispatch($template->getEvents());
            $this->eventStorage->add($template);
        } catch (\Exception $e) {
            $responder->templateUpdateFailed($e);
        }

        $template->removeDrafts();
        try {
            $this->eventBus->dispatch($template->getEvents());
            $this->eventStorage->add($template);
        } catch(\Exception $e) {
            $responder->templateUpdateFailed($e);
        }

        $responder->templateSuccessfullyUpdated($template, $templateVersion);
    }
}
