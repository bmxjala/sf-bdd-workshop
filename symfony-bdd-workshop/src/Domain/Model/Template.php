<?php

namespace Domain\Model;

use Domain\EventModel\Event\MessageBasedOnTemplateHasBeenComposed;
use Domain\EventModel\Event\TemplatesDraftsWereRemoved;
use Domain\EventModel\Event\TemplatesThemeWasUpdated;
use Domain\EventModel\Event\TemplatesVersionWasUpdated;
use Domain\EventModel\Event\TemplatesDraftWasUpdated;
use Domain\EventModel\Event\TemplateWasCreated;
use Domain\EventModel\AggregateHistory;
use Domain\EventModel\EventBased;
use Domain\EventModel\EventSourced;
use Domain\Model\Mailing\Message;
use Domain\Model\Template\TemplateId;

class Template implements EventBased
{
    use EventSourced;

    /**
     * @var TemplateId
     */
    private $templateId;

    /**
     * @var $currentVersion TemplateVersion
     */
    private $currentVersion;

    /**
     * @var $currentVersion TemplateVersion[]
     */
    private $templateVersions = [];

    /**
     * @var $templateDrafts TemplateDraft[]
     */
    private $templateDrafts = [];

    /**
     * @var $theme Theme
     */
    private $theme;

    /**
     * @param TemplateId $templateId
     */
    public function __construct(TemplateId $templateId)
    {
        $this->templateId = $templateId;
    }

    /**
     * @return TemplateId
     */
    public function getAggregateId()
    {
        return $this->templateId;
    }

    /**
     * @return TemplateVersion
     */
    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }

    /**
     * @return TemplateVersion
     */
    public function getTemplateDraft()
    {
        return end($this->templateDrafts);
    }

    /**
     * @return Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param TemplateVersion $templateVersion
     */
    public function updateVersion(TemplateVersion $templateVersion)
    {
        $this->recordThat($event = new TemplatesVersionWasUpdated($this->getAggregateId(), $templateVersion));
        $this->apply($event);
    }

    /**
     * @param TemplateDraft $templateDraft
     */
    public function updateDraft(TemplateDraft $templateDraft)
    {
        $this->recordThat($event = new TemplatesDraftWasUpdated($this->getAggregateId(), $templateDraft));
        $this->apply($event);
    }

    public function updateTheme(Theme $theme)
    {
        $this->recordThat($event = new TemplatesThemeWasUpdated($this, $theme));
        $this->apply($event);
    }

    public function removeDrafts()
    {
        $this->recordThat($event = new TemplatesDraftsWereRemoved($this->getAggregateId()));
        $this->apply($event);
    }

    public function recordComposedMessage(Message $message)
    {
        $this->recordThat($event = new MessageBasedOnTemplateHasBeenComposed($this->templateId, $this->getCurrentVersion(), $message));
    }

    private function applyTemplateWasCreated(TemplateWasCreated $event)
    {
        return $this;
    }

    private function applyTemplatesDraftsWereRemoved(TemplatesDraftsWereRemoved $event)
    {
        $this->templateDrafts = [];

        return $this;
    }

    private function applyTemplatesVersionWasUpdated(TemplatesVersionWasUpdated $event)
    {
        $this->addTemplateVersion($event->getCurrentVersion());
        $this->currentVersion = $event->getCurrentVersion();

        return $this;
    }

    private function applyMessageBasedOnTemplateHasBeenComposed()
    {
        return $this;
    }

    private function applyTemplatesThemeWasUpdated(TemplatesThemeWasUpdated $event)
    {
        $this->theme = $event->getTheme();

        return $this;
    }

    private function applyTemplatesDraftWasUpdated(TemplatesDraftWasUpdated $event)
    {
        $this->addTemplateDraft($event->getTemplateDraft());

        return $this;
    }

    private function addTemplateVersion(TemplateVersion $templateVersion)
    {
        $this->templateVersions[] = $templateVersion;
    }

    private function addTemplateDraft(TemplateDraft $templateDraft)
    {
        $this->templateDrafts[] = $templateDraft;
    }

    /**
     * @param AggregateHistory $aggregateHistory
     * @return Template
     */
    public static function reconstituteFrom(AggregateHistory $aggregateHistory)
    {
        $templateId = $aggregateHistory->getAggregateId();
        $template = new self($templateId);
        $events = $aggregateHistory->getEvents();

        foreach ($events as $event) {
            $applyMethod = explode('\\', get_class($event));
            $applyMethod = 'apply' . end($applyMethod);
            $template->$applyMethod($event);
        }

        return $template;
    }

    /**
     * @return Template
     */
    public static function create()
    {
        $template = new self(TemplateId::generate());
        $template->recordThat(new TemplateWasCreated($template->templateId));

        return $template;
    }

}
