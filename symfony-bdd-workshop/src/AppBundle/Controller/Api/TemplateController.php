<?php

namespace AppBundle\Controller\Api;

use Domain\EventModel\AggregateHistory\TemplateAggregateHistory;
use Domain\Model\Template;
use Domain\ReadModel\Projection\TemplateRenderFormProjection;
use Domain\UseCase\ShowRenderTemplateForm;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Domain\UseCase\ListTemplates;
use Domain\ReadModel\Projection\TemplateListProjection;
use AppBundle\Entity as AppEntity;

class TemplateController extends FOSRestController implements ListTemplates\Responder, ShowRenderTemplateForm\Responder
{
    private $response;

    /**
     * @var AppEntity\Template
     */
    private $template;

    /**
     * Returns list of templates.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns list of templates",
     *  statusCodes={
     *      200="Returned when successful"
     *  }
     * )
     */
    public function getTemplatesAction()
    {
        $listTemplatesUseCase = new ListTemplates(
            $this->get('projection_storage')
        );
        $listTemplatesUseCase->execute(new ListTemplates\Command(), $this);

        return $this->handleView($this->response);
    }

    /**
     * Returns template by its id.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns template by its id",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when template with given ID does not exist"
     *  }
     * )
     */
    public function getTemplateAction($id)
    {
        $domainTemplate = $this->getTemplate($id);

        $this->template = AppEntity\Template::createFromDomainObject($domainTemplate);

        $showRenderTemplateFormUseCase = new ShowRenderTemplateForm(
            $this->get('projection_storage')
        );
        $showRenderTemplateFormUseCase->execute(new ShowRenderTemplateForm\Command(new Template\TemplateId($id)), $this);

        return $this->handleView($this->response);
    }

    /**
     * @param $projections TemplateListProjection[]
     */
    public function templateListedSuccessfully(array $projections)
    {
        $templates = [];
        foreach($projections as $projection) {
            $template = AppEntity\TemplateListItem::createFromProjection($projection);
            $templates[] = $template;
        }

        $this->response = $this->view($templates, 200);
    }

    /** {@inheritdoc} */
    public function templateRenderFormFetchedSuccessfully(TemplateRenderFormProjection $projection, $type = null)
    {
        $this->template->setRenderFields($projection->getFields());
        $this->response = $this->view($this->template, 200);
    }

    /** {@inheritdoc} */
    public function templateRenderFormNotFound(Template\TemplateId $templateId)
    {
        throw new HttpException(500, 'Problems occured while getting template render fields');
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
}

