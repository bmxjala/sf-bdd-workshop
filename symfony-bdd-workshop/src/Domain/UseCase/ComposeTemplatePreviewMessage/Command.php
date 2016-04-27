<?php

namespace Domain\UseCase\ComposeTemplatePreviewMessage;

use Domain\Model\Mailing\Recipient;
use Domain\Model\Template;

class Command
{
    /**
     * @var Recipient
     */
    public $recipient;

    /**
     * @var Template
     */
    public $template;

    /**
     * @var array
     */
    public $fields;

    function __construct(Recipient $recipient, Template $template, array $fields)
    {
        $this->recipient = $recipient;
        $this->template = $template;
        $this->fields = $fields;
    }
}
