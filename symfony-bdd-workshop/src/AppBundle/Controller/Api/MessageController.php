<?php

namespace AppBundle\Controller\Api;

use Domain\EventModel\AggregateHistory\TemplateAggregateHistory;
use Domain\Exception\MissingTemplateFieldsException;
use Domain\Model\Mailing\EmailAddress;
use Domain\Model\Mailing\Mailer;
use Domain\Model\Mailing\Message;
use Domain\Model\Mailing\Recipient;
use Domain\Model\Mailing\Sender;
use Domain\Model\Template;
use Domain\Model\Template\TemplateId;
use Domain\Model\User\UserId;
use Domain\UseCase\ComposeMessage;
use FOS\RestBundle\Controller\FOSRestController;
use Domain\UseCase\UpdateTemplateDraft;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MessageController extends FOSRestController implements ComposeMessage\Responder
{
    private $response;

    /**
     * @var Recipient
     */
    private $messageRecipient;

    /**
     * @var Sender
     */
    private $messageSender;

    /**
     * @var string
     */
    private $messageSubject;

    /**
     * @var Template
     */
    private $messageTemplate;

    /**
     * @var array
     */
    private $messageTemplateFields;

    /**
     * Sends email message basing on given template and its required render-data.
     *
     * Each template content could contain a number of placeholders for rendering dynamic data, like recipients name.
     * If a template contains a value in handlebars (e.g. `{{username}}`) then you should set
     * `render_data[username] = "User's name here"` param, so message content will contain desired value instead handlebars.
     *
     *
     * @ApiDoc(
     *   resource=true,
     *   description="Sends email message basing on template",
     *   parameters={
     *      {"name"="templateid", "dataType"="string", "description"="Valid template id", "required"=true},
     *      {"name"="recipient", "dataType"="email", "description"="Valid email address of recipient", "required"=true},
     *      {"name"="sender", "dataType"="email", "description"="Valid email address of sender", "required"=true},
     *      {"name"="sender_name", "dataType"="string", "description"="Full name of sender", "required"=false},
     *      {"name"="subject", "dataType"="string", "description"="Subject of message", "required"=true},
     *      {"name"="render_data", "dataType"="array", "description"="Key-value array of data that should be rendered in each of handlebars", "required"=false}
     *   },
     *   statusCodes={
     *      201="Returned when successfully sent",
     *      400="Returned on invalid request (e.g. missing obligatory param)",
     *      404="Returned on invalid templateid provided"
     *   }
     * )
     */
    public function postMessageAction(Request $request)
    {
        $this->validate($request);
        $this->retrieveMessageData($request);

        $command = new ComposeMessage\Command(
            $this->messageRecipient,
            $this->messageSender,
            $this->messageSubject,
            $this->messageTemplate,
            $this->messageTemplateFields
        );

        try {
            $composeMessageUseCase = new ComposeMessage(
                $this->get('event_bus'),
                $this->get('event_storage')
            );
            $composeMessageUseCase->execute($command, $this);
        } catch(MissingTemplateFieldsException $e) {
            throw new HttpException(400, $e->getMessage());
        } catch(\Exception $e) {
            throw new HttpException(500);
        }

        return $this->handleView($this->response);
    }

    /** {@inheritdoc} */
    public function messageSuccessfullyComposed(Message $message)
    {
        if($this->container->get('kernel')->getEnvironment() === 'test') {
            $this->response = $this->view($message, 201);
            return;
        }

        /** @var Mailer $mailer */
        $mailer = $this->get('mailer');
        $result = $mailer->send($message);

        if($result === false) {
            throw new HttpException(500, 'Message sending failed');
        }

        $this->response = $this->view($message, 201);
    }

    private function getTemplate($id)
    {
        $templateId = new TemplateId($id);
        $templateAggregateHistory = new TemplateAggregateHistory($templateId, $this->get('event_storage'));
        if(empty($templateAggregateHistory->getEvents())) {
            throw new HttpException(404, 'Invalid templateid provided');
        }

        return Template::reconstituteFrom($templateAggregateHistory);
    }

    private function validate(Request $request)
    {
        if(empty($request->get('subject'))) {
            throw new HttpException(400, 'Empty required param: subject');
        }
        if(empty($request->get('recipient'))) {
            throw new HttpException(400, 'Empty required param: recipient');
        }
        if(empty($request->get('sender'))) {
            throw new HttpException(400, 'Empty required param: sender');
        }
        if(empty($request->get('templateid'))) {
            throw new HttpException(400, 'Empty required param: templateid');
        }
    }

    private function retrieveMessageData(Request $request)
    {
        $this->messageRecipient = new Recipient(new EmailAddress($request->get('recipient')));
        $this->messageSender = new Sender(new EmailAddress($request->get('sender')));
        $this->messageSubject = $request->get('subject');
        $this->messageTemplate = $this->getTemplate($request->get('templateid'));
        $this->messageTemplateFields = $request->get('render_data');
        if(!empty($request->get('sender_name'))) {
            $this->messageSender->setFullName($request->get('sender_name'));
        }
    }

}
