<?php

namespace Domain\Model;

use Domain\EventModel\Event\TemplateVersionWasCreated;
use Domain\EventModel\Event\TemplateVersionWasUpdated;
use Domain\EventModel\AggregateHistory;
use Domain\EventModel\EventBased;
use Domain\EventModel\EventSourced;
use Domain\Exception\MissingTemplateFieldsException;
use Domain\Model\Template\TemplateVersionId;
use Domain\Model\User\UserId;
use Domain\Model\Template\TemplateId;

class TemplateVersion implements EventBased
{
    use EventSourced;
    use DynamicContent;

    /**
     * @var TemplateVersionId
     */
    private $templateVersionId;

    /**
     * @var UserId
     */
    private $authorId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $plaintextContent;

    /**
     * @var string
     */
    private $htmlContent;

    public function __construct(TemplateVersionId $templateVersionId, UserId $authorId)
    {
        $this->templateVersionId = $templateVersionId;
        $this->authorId = $authorId;
    }

    /**
     * @return TemplateVersionId
     */
    public function getAggregateId()
    {
        return $this->templateVersionId;
    }

    /**
     * @return UserId
     */
    public function getAuthor()
    {
        return $this->authorId;
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
        $content = $this->render($this->getPlaintextContent(), $values);

        $missingFields = $this->extract($content);
        if(!empty($missingFields)) {
            throw new MissingTemplateFieldsException($missingFields);
        }

        return $content;
    }

    /**
     * @param Theme $theme
     * @param array $values
     * @return string
     */
    public function renderHtml(Theme $theme, array $values)
    {
        $main = $this->render($this->getHtmlContent(), $values);

        $missingFields = $this->extract($main);
        if(!empty($missingFields)) {
            throw new MissingTemplateFieldsException($missingFields);
        }

        return $this->render($theme->getContent(), ['main' => $main]);
    }

    /**
     * @param $name
     * @param $plaintextContent
     * @param $htmlContent
     */
    public function update($name, $plaintextContent, $htmlContent)
    {
        $this->recordThat($event = new TemplateVersionWasUpdated($this->getAggregateId(), $name, $plaintextContent, $htmlContent));
        $this->apply($event);
    }

    private function applyTemplateVersionWasCreated(TemplateVersionWasCreated $event)
    {
        return $this;
    }

    private function applyTemplateVersionWasUpdated(TemplateVersionWasUpdated $event)
    {
        $this->name = $event->getName();
        $this->plaintextContent = $event->getPlaintextContent();
        $this->htmlContent = $event->getHtmlContent();

        return $this;
    }

    /**
     * @param AggregateHistory $aggregateHistory
     * @param TemplateId $templateId
     * @param UserId $authorId
     * @return TemplateVersion
     */
    public static function reconstituteFrom(AggregateHistory $aggregateHistory, UserId $authorId)
    {
        $templateVersionId = $aggregateHistory->getAggregateId();
        $templateVersion = new self($templateVersionId, $authorId);
        foreach ($aggregateHistory->getEvents() as $event) {
            $applyMethod = explode('\\', get_class($event));
            $applyMethod = 'apply' . end($applyMethod);
            $templateVersion->$applyMethod($event);
        }

        return $templateVersion;
    }

    /**
     * @param TemplateId $templateId
     * @param UserId $author
     * @return TemplateVersion
     */
    public static function create(UserId $author)
    {
        $templateVersion = new self(TemplateVersionId::generate(), $author);
        $templateVersion->recordThat(new TemplateVersionWasCreated($templateVersion->templateVersionId));

        return $templateVersion;
    }
}
