<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Traits\SanitizeHtml;
use Domain\EventModel\AggregateHistory\TemplateAggregateHistory;
use Domain\Model\Template;
use Domain\Model\TemplateDraft;
use Domain\Model\TemplateVersion;
use Domain\Model\Theme\ThemeId;
use Domain\Model\User\UserId;
use Domain\ReadModel\Projection\TemplateDraftListProjection;
use Domain\ReadModel\Projection\TemplateListProjection;
use Domain\ReadModel\Projection\TemplateRenderFormProjection;
use Domain\ReadModel\Projection\ThemeListProjection;
use Domain\UseCase\CreateTemplate;
use Domain\UseCase\ListTemplates;
use Domain\UseCase\ListTemplateDraft;
use Domain\UseCase\ListThemes;
use Domain\UseCase\ShowRenderTemplateForm;
use Domain\UseCase\UpdateTemplateVersion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\VarDumper\VarDumper;

class EditorController extends Controller implements UpdateTemplateVersion\Responder, CreateTemplate\Responder, ListTemplates\Responder, ListTemplateDraft\Responder, ShowRenderTemplateForm\Responder, ListThemes\Responder
{
    use SanitizeHtml;

    private $response;

    private $renderTemplateFormContext;

    public function listAction()
    {
        $this->response = [
            'templates' => [],
            'drafts' => []
        ];

        $listTemplatesUseCase = new ListTemplates(
            $this->get('projection_storage')
        );
        $listTemplatesUseCase->execute(new ListTemplates\Command(), $this);

        $listTemplateDraftsUseCase = new ListTemplateDraft(
            $this->get('projection_storage')
        );
        $listTemplateDraftsUseCase->execute(new ListTemplateDraft\Command(new UserId('to-be-done')), $this);

        return $this->render('AppBundle:Editor:list.html.twig', $this->response);
    }

    public function createAction()
    {
        $createTemplateUseCase = new CreateTemplate($this->get('event_bus'), $this->get('event_storage'));
        $createTemplateUseCase->execute(new CreateTemplate\Command(new UserId()), $this);

        return $this->response;
    }

    public function editAction($id)
    {
        $templateData = $this->retrieveTemplateData($id);
        $template = $this->getTemplate($id);

        $listThemesUseCase = new ListThemes($this->get('projection_storage'));
        $listThemesUseCase->execute(new ListThemes\Command(), $this);

        $form = $this->createFormBuilder($templateData)
            ->setAction($this->generateUrl('editor_template_save', ['id' => $id]))
            ->setMethod('POST')
            ->add('templateid', HiddenType::class, ['data' => $id])
            ->add('theme', ChoiceType::class, $this->prepareThemeChoices($templateData->theme))
            ->add('name', TextType::class)
            ->add('plaintext_content', TextareaType::class)
            ->add('html_content', TextareaType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Save'
            ])
            ->getForm()
        ;

        return $this->render('AppBundle:Editor:edit.html.twig', [
            'templateid' => $id,
            'template' => $template,
            'form' => $form->createView()
        ]);
    }

    public function saveAction($id, Request $request)
    {
        if ($request->getMethod() != 'POST') {
            throw new HttpException(405);
        }

        $form = $this->createFormBuilder()->getForm()->handleRequest($request);
        $formData = $form->getExtraData();

        $command = new UpdateTemplateVersion\Command(
            new Template\TemplateId($id),
            Template\TemplateVersionId::generate(), // @todo
            new UserId(time()), // @todo
            $formData['name'],
            $formData['plaintext_content'],
            $formData['html_content'],
            new ThemeId($formData['theme'])
        );

        $updateTemplateUseCase = new UpdateTemplateVersion(
            $this->get('event_bus'),
            $this->get('event_storage')
        );
        $updateTemplateUseCase->execute($command, $this);

        return $this->response;
    }

    public function renderTemplateFormForPreviewAction($id, $type)
    {
        $this->renderTemplateFormContext = 'preview';

        if(!in_array($type, ['text', 'html'])) {
            throw new HttpException(400, 'Invalid preview type: ' . $type);
        }

        $showRenderTemplateFormUseCase = new ShowRenderTemplateForm(
            $this->get('projection_storage')
        );
        $showRenderTemplateFormUseCase->execute(new ShowRenderTemplateForm\Command(new Template\TemplateId($id), $type), $this);

        return $this->response;
    }

    public function renderTemplateFormForMessageAction($id)
    {
        $this->renderTemplateFormContext = 'message';

        $showRenderTemplateFormUseCase = new ShowRenderTemplateForm(
            $this->get('projection_storage')
        );
        $showRenderTemplateFormUseCase->execute(new ShowRenderTemplateForm\Command(new Template\TemplateId($id), 'html'), $this);

        return $this->response;
    }

    public function previewAction($id, $type, Request $request)
    {
        $template = $this->getTemplate($id);
        if(empty($template->getCurrentVersion())) {
            throw new HttpException(404);
        }

        $renderTemplateFields = [];
        if ($request->getMethod() == 'POST') {
            $renderTemplateFields = $this->retrieveRenderTemplateFields($request);
        }

        $content = '';
        switch($type) {
            case 'text':
                $content = $template->getCurrentVersion()->renderPlaintext($renderTemplateFields);
                return new Response($content, 200, ['Content-type' => 'text/plain']);
                break;
            case 'html':
                $content = $template->getCurrentVersion()->renderHtml($template->getTheme(), $renderTemplateFields);
                return new Response($this->stripScript($content));
                break;
        }
        throw new HttpException(400);
    }

    public function templateSuccessfullyCreated(
        Template $template,
        TemplateVersion $templateVersion,
        TemplateDraft $templateDraft
    ) {
        $this->response = $this->redirect($this->generateUrl('editor_template_edit', [
            'id' => $template->getAggregateId()
        ]));
    }

    public function templateSuccessfullyUpdated(
        Template $template,
        TemplateVersion $templateVersion
    ) {
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Template "'.$templateVersion->getName().'" has been saved'
        );

        $this->response = $this->redirect($this->generateUrl('editor_template_list'));
    }

    public function templateUpdateFailed(\Exception $e)
    {
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Template saving failed due to following reason: ' . $e->getMessage()
        );

        $this->response = $this->redirect($this->generateUrl('editor_template_list'));
    }

    public function templateCreatingFailed(\Exception $e)
    {
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Template creating failed due to following reason: ' . $e->getMessage()
        );

        $this->response = $this->redirect($this->generateUrl('editor_template_list'));
    }

    /**
     * @param $projections TemplateListProjection[]
     */
    public function templateListedSuccessfully(array $projections)
    {
        $this->response['templates'] = $projections;
    }

    /**
     * @param $projections TemplateDraftListProjection[]
     */
    public function templateDraftListedSuccessfully(array $projections)
    {
        $this->response['drafts'] = $projections;
    }

    /**
     * @param ThemeListProjection[] $projections
     */
    public function themeListedSuccessfully(array $projections)
    {
        $this->response['themes'] = $projections;
    }

    /**
     * @param $projection TemplateRenderFormProjection
     * @param $type 'text|html'
     */
    public function templateRenderFormFetchedSuccessfully(TemplateRenderFormProjection $projection, $type = null)
    {
        $templateRenderFields = new \stdClass();
        $templateRenderFields->templateid = $projection->getAggregateId();

        $form = $this->createFormBuilder($templateRenderFields)
            ->add('templateid', HiddenType::class, ['data' => $projection->getAggregateId()])
            ->setMethod('POST');

        switch ($type) {
            case 'text':
                if (empty($projection->getPlaintextFields())) {
                    $this->response = $this->redirectToRoute('editor_template_preview', [
                        'id' => $projection->getAggregateId(),
                        'type' => $type
                    ]);
                    return;
                }
                $this->fetchFields($form, $templateRenderFields, $projection->getPlaintextFields());
                break;
            case 'html':
                if (empty($projection->getHtmlFields())) {
                    $this->response = $this->redirectToRoute('editor_template_preview', [
                        'id' => $projection->getAggregateId(),
                        'type' => $type
                    ]);
                    return;
                }
                $this->fetchFields($form, $templateRenderFields, $projection->getHtmlFields());
                break;
            default:
                if (empty($projection->getFields())) {
                    $this->response = $this->redirectToRoute('editor_template_preview', [
                        'id' => $projection->getAggregateId()
                    ]);
                    return;
                }
                $this->fetchFields($form, $templateRenderFields, $projection->getFields());
                break;
        }

        if ($this->renderTemplateFormContext == 'preview') {
            $form->add('preview', SubmitType::class, [
                'label' => 'Preview',
                'attr' => ['class' => 'button round'],
            ]);
            $form->setAction($this->generateUrl('editor_template_preview', ['id' => $projection->getAggregateId(), 'type' => $type]));
        } elseif ($this->renderTemplateFormContext == 'message') {
            $form->setAction($this->generateUrl('mailer_send_template_preview'));
        }

        if($this->renderTemplateFormContext == 'message') {
            $templateRenderFields->preview_recipient_email = '';
            $form->add('preview_recipient_email', TextType::class, [
                'label' => 'Send preview message to',
                'required' => false
            ]);
            $form->add('send', SubmitType::class, [
                'label' => 'Send',
                'attr' => ['class' => 'success button round'],
            ]);
        }

        $this->response = $this->render('AppBundle:Editor:render-template-form.html.twig', [
            'templateid' => $projection->getAggregateId(),
            'template' => $this->retrieveTemplateData((string) $projection->getAggregateId()),
            'type' => $type,
            'context' => $this->renderTemplateFormContext,
            'form' => $form->getForm()->createView(),
        ]);
    }

    /**
     * @param $templateId Template\TemplateId
     */
    public function templateRenderFormNotFound(Template\TemplateId $templateId)
    {
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Template render form not found for template: ' . $templateId
        );

        $this->response = $this->redirect($this->generateUrl('editor_template_list'));
    }

    private function retrieveTemplateData($templateId)
    {
        $template = $this->getTemplate($templateId);

        /** @var $currentTemplateVersion TemplateVersion */
        $currentTemplateVersion = $template->getCurrentVersion();

        $templateData = new \stdClass();
        $templateData->templateid = $templateId;

        if(!empty($templateDraft = $template->getTemplateDraft())) {
            $templateData->name = $templateDraft->getName();
            $templateData->plaintext_content = $templateDraft->getPlaintextContent();
            $templateData->html_content = $templateDraft->getHtmlContent();
        } else {
            $templateData->name = empty($currentTemplateVersion) ? '' : $currentTemplateVersion->getName();
            $templateData->plaintext_content = empty($currentTemplateVersion) ? '' : $currentTemplateVersion->getPlaintextContent();
            $templateData->html_content = empty($currentTemplateVersion) ? '' : $currentTemplateVersion->getHtmlContent();
        }

        $templateData->theme = !empty($theme = $template->getTheme()) ? $theme->getAggregateId() : null;

        return $templateData;
    }

    private function fetchFields($form, $templateRenderFields, $projectionFields)
    {
        foreach ($projectionFields as $field) {
            $templateRenderFields->$field = '';
            $form->add($field, TextareaType::class);
        }
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
        unset($fields['preview']);
        unset($fields['_token']);
        unset($fields['preview_recipient_email']);

        return $fields;
    }

    private function prepareThemeChoices($themeId = null)
    {
        $data = [
            'choices_as_values' => true,
            'data' => $themeId,
        ];

        foreach ($this->response['themes'] as $theme) {
            /** @var ThemeListProjection $theme */
            $data['choices'][$theme->getName()] = (string)$theme->getAggregateId();
        }

        return $data;
    }
}
