<?php

namespace Domain\Model;

use Domain\EventModel\Event\TemplateDraftWasCreated;
use Domain\EventModel\AggregateHistory;
use Domain\EventModel\EventBased;
use Domain\EventModel\EventSourced;
use Domain\Model\Template\TemplateDraftId;
use Domain\Model\Template\TemplateId;
use Domain\Model\User\UserId;

class TemplateDraft implements EventBased
{
    use EventSourced;
    use DynamicContent;

    private $templateDraftId;
    private $templateId;
    private $owner;
    private $name;
    private $plaintextContent;
    private $htmlContent;

    /**
     * @param TemplateDraftId $templateDraftId
     * @param TemplateId $templateId
     * @param UserId $owner
     */
    public function __construct(TemplateDraftId $templateDraftId, TemplateId $templateId, UserId $owner)
    {
        $this->templateDraftId = $templateDraftId;
        $this->templateId = $templateId;
        $this->owner = $owner;
    }

    /**
     * @return TemplateDraftId
     */
    public function getAggregateId()
    {
        return $this->templateDraftId;
    }

    /**
     * @return UserId
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPlaintextContent()
    {
        return $this->plaintextContent;
    }

    /**
     * @return string
     */
    public function getHtmlContent()
    {
        return $this->htmlContent;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $plaintextContent
     */
    public function setPlaintextContent($plaintextContent)
    {
        $this->plaintextContent = $plaintextContent;
    }

    /**
     * @param string $htmlContent
     */
    public function setHtmlContent($htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }

    /**
     * @return array
     */
    public function extractFromPlaintext()
    {
        return $this->extract($this->getPlaintextContent());
    }

    /**
     * @return array
     */
    public function extractFromHtml()
    {
        return $this->extract($this->getHtmlContent());
    }

    /**
     * @param array $values
     * @return string
     */
    public function renderPlaintext(array $values)
    {
        return $this->render($this->getPlaintextContent(), $values);
    }

    /**
     * @param Theme $theme
     * @param array $values
     * @return string
     */
    public function renderHtml(Theme $theme, array $values)
    {
        $main = $this->render($this->getHtmlContent(), $values);

        return $this->render($theme->getContent(), ['main' => $main]);
    }

    private function applyTemplateDraftWasCreated(TemplateDraftWasCreated $event)
    {
        return $this;
    }

    /**
     * @param AggregateHistory $aggregateHistory
     * @param TemplateId $templateId
     * @param UserId $userId
     * @return TemplateDraft
     */
    public static function reconstituteFrom(AggregateHistory $aggregateHistory, TemplateId $templateId, UserId $userId)
    {
        $templateDraftId = $aggregateHistory->getAggregateId();
        $templateDraft = new self($templateDraftId, $templateId, $userId);
        foreach ($aggregateHistory->getEvents() as $event) {
            $applyMethod = explode('\\', get_class($event));
            $applyMethod = 'apply' . end($applyMethod);
            $templateDraft->$applyMethod($event);
        }

        return $templateDraft;
    }

    /**
     * @param TemplateId $templateId
     * @param UserId $author
     * @return TemplateDraft
     */
    public static function create(TemplateId $templateId, UserId $author)
    {
        $templateDraft = new self(TemplateDraftId::generate(), $templateId, $author);
        $templateDraft->recordThat(new TemplateDraftWasCreated($templateDraft->templateDraftId));

        return $templateDraft;
    }
}
