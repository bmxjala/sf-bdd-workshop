<?php

namespace Domain\ReadModel\Projection;

use Domain\Model\Mailing\Recipient;
use Domain\Model\Mailing\Sender;
use Domain\Model\Reports\MessageId;
use Domain\Model\Template\TemplateId;
use Domain\Model\TemplateVersion;
use Domain\ReadModel\Projection;

class SendingReportsProjection implements Projection
{
    const PROJECTION_NAME = 'sending-reports';

    /**
     * @var MessageId
     */
    private $messageId;

    /**
     * @var Recipient
     */
    private $recipient;

    /**
     * @var Sender
     */
    private $sender;

    /**
     * @var TemplateId
     */
    private $templateId;

    /**
     * @var TemplateVersion
     */
    private $templateVersion;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $html;

    /**
     * @param MessageId $messageId
     * @param Recipient $recipient
     * @param Sender $sender
     * @param TemplateId $templateId
     * @param TemplateVersion $templateVersion
     * @param \DateTime $date
     * @param string $subject
     * @param string $text
     * @param string $html
     */
    public function __construct(
        MessageId $messageId,
        Recipient $recipient,
        Sender $sender,
        TemplateId $templateId,
        TemplateVersion $templateVersion,
        \DateTime $date,
        $subject,
        $text,
        $html
    )
    {
        $this->messageId = $messageId;
        $this->recipient = $recipient;
        $this->sender = $sender;
        $this->templateId = $templateId;
        $this->templateVersion = $templateVersion;
        $this->date = $date;
        $this->subject = $subject;
        $this->text = $text;
        $this->html = $html;
    }


    /**
     * @return string
     */
    public function getProjectionName()
    {
        return self::PROJECTION_NAME;
    }

    /**
     * @return MessageId
     */
    public function getAggregateId()
    {
        return $this->messageId;
    }

    /**
     * @return Recipient
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @return Sender
     */
    public function getSender()
    {
        return $this->sender;
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
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }
}
