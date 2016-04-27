<?php

namespace Domain\ReadModel\Listener;

use Domain\EventModel\Event\MessageBasedOnTemplateHasBeenComposed;
use Domain\Model\Reports\MessageId;
use Domain\ReadModel\AbstractDomainEventListener;
use Domain\ReadModel\DomainEventListener;
use Domain\ReadModel\Projection\SendingReportsProjection;

class SendingReportsListener extends AbstractDomainEventListener implements DomainEventListener
{
    public function onMessageBasedOnTemplateHasBeenComposed(MessageBasedOnTemplateHasBeenComposed $event)
    {
        $sendingReportsProjection = new SendingReportsProjection(
            $event->getAggregateId(),
            $event->getComposedMessage()->getRecipient(),
            $event->getComposedMessage()->getSender(),
            $event->getTemplateId(),
            $event->getTemplateVersion(),
            $event->getDate(),
            $event->getComposedMessage()->getSubject(),
            $event->getComposedMessage()->getText(),
            $event->getComposedMessage()->getHtml()
        );

        $this->projectionStorage->save($sendingReportsProjection);
    }
}
