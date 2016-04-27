<?php

namespace AppBundle\Service;

use Domain\Model\Mailing\Mailer;
use Domain\Model\Mailing\Message as DomainMessage;
use Hip\MandrillBundle\Message as MandrillMessage;
use Hip\MandrillBundle\Dispatcher as MandrillDispatcher;

class HipMandrillMailer implements Mailer
{
    /**
     * @var $dispatcher MandrillDispatcher
     */
    private $dispatcher;

    /**
     * @param MandrillDispatcher $dispatcher
     */
    public function __construct(MandrillDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param DomainMessage $domainMessage
     * @return array|bool
     */
    public function send(DomainMessage $domainMessage)
    {
        $mandrillMessage = new MandrillMessage();
        $mandrillMessage
            ->setFromEmail((string) $domainMessage->getSender()->getEmailAddress())
            ->setFromName($domainMessage->getSender()->getFullName())
            ->addTo((string) $domainMessage->getRecipient()->getEmailAddress())
            ->setSubject($domainMessage->getSubject())
            ->setHtml($domainMessage->getHtml())
            ->setText($domainMessage->getText());

        return $this->dispatcher->send($mandrillMessage);
    }

}
