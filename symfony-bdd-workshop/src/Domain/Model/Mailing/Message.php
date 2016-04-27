<?php

namespace Domain\Model\Mailing;

class Message
{
    /**
     * @var Recipient
     */
    private $recipient;

    /**
     * @var Sender
     */
    private $sender;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $html;

    /**
     * @var string
     */
    private $text;

    /**
     * @param Recipient $recipient
     * @param Sender $sender
     * @param string $subject
     * @param string $html
     * @param string $text
     */
    public function __construct(
        Recipient $recipient,
        Sender $sender,
        $subject,
        $html,
        $text
    )
    {
        $this->recipient = $recipient;
        $this->sender = $sender;
        $this->subject = $subject;
        $this->html = $html;
        $this->text = $text;
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
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
