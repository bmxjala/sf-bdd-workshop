<?php

namespace Domain\EventModel\Event;

use Domain\EventModel\DomainEvent;
use Domain\Model\Mailing\Message;
use Domain\Model\Reports\MessageId;
use Domain\Model\Template\TemplateId;
use Domain\Model\TemplateVersion;

class MessageBasedOnTemplateHasBeenComposed implements DomainEvent
{
    /**
     * @var MessageId
     */
    private $messageId;

    /**
     * @var TemplateId
     */
    private $templateId;

    /**
     * @var Message
     */
    private $composedMessage;

    /**
     * @var TemplateVersion
     */
    private $templateVersion;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @param TemplateId $templateId
     * @param TemplateVersion $templateVersion
     * @param Message $message
     */
    public function __construct(TemplateId $templateId, TemplateVersion $templateVersion, Message $message)
    {
        $this->messageId = MessageId::generate();
        $this->templateId = $templateId;
        $this->templateVersion = $templateVersion;
        $this->composedMessage = $message;
        $this->date = new \DateTime();
    }

    /**
     * @return MessageId
     */
    public function getAggregateId()
    {
        return $this->messageId;
    }

    /**
     * @return TemplateId
     */
    public function getTemplateId()
    {
        return $this->templateId;
    }

    /**
     * @return TemplateVersion
     */
    public function getTemplateVersion()
    {
        return $this->templateVersion;
    }

    /**
     * @return Message
     */
    public function getComposedMessage()
    {
        return $this->composedMessage;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
