<?php

namespace AppBundle\Controller;

use Domain\EventModel\AggregateHistory\TemplateAggregateHistory;
use Domain\Model\Mailing\EmailAddress;
use Domain\Model\Mailing\Mailer;
use Domain\Model\Mailing\Message;
use Domain\Model\Mailing\Recipient;
use Domain\Model\Template;
use Domain\UseCase\ComposeTemplatePreviewMessage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MailerController extends Controller implements ComposeTemplatePreviewMessage\Responder
{
    private $response;

    public function sendPreviewAction(Request $request)
    {
        $formData = $request->request->get('form');

        $command = new ComposeTemplatePreviewMessage\Command(
            new Recipient(new EmailAddress($formData['preview_recipient_email'])),
            $this->getTemplate($formData['templateid']),
            $this->retrieveRenderTemplateFields($request)
        );

        $composeTemplatePreviewMessageUseCase = new ComposeTemplatePreviewMessage();
        $composeTemplatePreviewMessageUseCase->execute($command, $this);

        return $this->response;
    }

    public function mailSuccessfullyComposed(Message $message)
    {
        /** @var Mailer $mailer */
        $mailer = $this->get('mailer');
        $result = $mailer->send($message);

        if($result === false) {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Template preview sending FAILED!'
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Template preview has been successfully sent to you'
            );
        }

        $this->response = $this->redirect($this->generateUrl('editor_template_list'));
    }

    private function getTemplate($id)
    {
        $templateId = new Template\TemplateId($id);
        $templateAggregateHistory = new TemplateAggregateHistory($templateId, $this->get('event_storage'));
        if(empty($templateAggregateHistory->getEvents())) {
            throw new HttpException(404, 'Invalid templateid provided');
        }

        return Template::reconstituteFrom($templateAggregateHistory);
    }

    private function retrieveRenderTemplateFields(Request $request)
    {
        $fields = $request->request->get('form');
        unset($fields['templateid']);
        unset($fields['send']);
        unset($fields['_token']);
        unset($fields['preview_recipient_email']);

        return $fields;
    }
}
