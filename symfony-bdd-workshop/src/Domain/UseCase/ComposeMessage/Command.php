<?php

namespace Domain\UseCase\ComposeMessage;

use Domain\Model\Mailing\Recipient;
use Domain\Model\Mailing\Sender;
use Domain\Model\Template;

class Command
{
    /**
     * @var Recipient
     */
    public $recipient;

    /**
     * @var Sender
     */
    public $sender;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var Template
     */
    public $template;

    /**
     * @var array
     */
    public $fields;

    function __construct(Recipient $recipient, Sender $sender, $subject, Template $template, array $fields)
    {
        $this->recipient = $recipient;
        $this->sender = $sender;
        $this->subject = $subject;
        $this->template = $template;
        $this->fields = $fields;
    }
}
