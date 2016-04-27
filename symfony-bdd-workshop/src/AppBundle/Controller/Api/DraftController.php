<?php

namespace AppBundle\Controller\Api;

use Domain\Model\Template;
use Domain\Model\Template\TemplateId;
use Domain\Model\User\UserId;
use FOS\RestBundle\Controller\FOSRestController;
use Domain\UseCase\UpdateTemplateDraft;
use Symfony\Component\HttpFoundation\Request;

class DraftController extends FOSRestController implements UpdateTemplateDraft\Responder
{
    private $response;

    public function putDraftAction($templateId, Request $request)
    {
        $draftData = $this->retrieveDraftData($request);

        $updateTemplateDraftUseCase = new UpdateTemplateDraft($this->get('event_bus'), $this->get('event_storage'));
        $command = new UpdateTemplateDraft\Command(
            new TemplateId($templateId),
            new UserId('asd'),
            $draftData['name'],
            $draftData['plaintext_content'],
            $draftData['html_content']
        );
        try {
            $updateTemplateDraftUseCase->execute($command, $this);
        } catch  (\Exception $e) {
            $view = $this->view('error', 500);
            $this->response = $this->handleView($view);
        }

        return $this->response;
    }

    /**
     * @param Template $template
     */
    public function templateDraftSuccessfullyUpdated(Template $template)
    {
        $view = $this->view(204);
        $this->response = $this->handleView($view);
    }

    private function retrieveDraftData(Request $request)
    {
        return [
            'name' => $request->get('name'),
            'plaintext_content' => $request->get('plaintext_content'),
            'html_content' => $request->get('html_content')
        ];
    }
}
