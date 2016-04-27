<?php

namespace AppBundle\Controller;

use Domain\ReadModel\Projection\SendingReportsProjection;
use Domain\ReadModel\Projection\TemplateListProjection;
use Domain\ReadModel\Projection\ThemeListProjection;
use Domain\UseCase\ListTemplates;
use Domain\UseCase\ListThemes;
use Domain\UseCase\ShowSendingReports;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller implements ListTemplates\Responder, ListThemes\Responder, ShowSendingReports\Responder
{

    /**
     * @var ThemeListProjection[]
     */
    private $themes = [];

    /**
     * @var TemplateListProjection[]
     */
    private $templates = [];

    /**
     * @var SendingReportsProjection[]
     */
    private $reports = [];

    /**
     * @return Response
     */
    public function mainAction()
    {
        $this->listThemes();
        $this->listTemplates();
        $this->listReports();

        $data = [
            'counters' => [
                'themes' => count($this->themes),
                'templates' => count($this->templates),
                'reports' => count($this->reports),
            ],
        ];

        return $this->render('AppBundle:Dashboard:dashboard.html.twig', $data);
    }

    private function listThemes()
    {
        $listThemesUseCase = new ListThemes(
            $this->get('projection_storage')
        );
        $listThemesUseCase->execute(new ListThemes\Command(), $this);
    }

    private function listTemplates()
    {
        $listTemplatesUseCase = new ListTemplates(
            $this->get('projection_storage')
        );
        $listTemplatesUseCase->execute(new ListTemplates\Command(), $this);
    }

    private function listReports()
    {
        $showSendingReportsUseCase = new ShowSendingReports(
            $this->get('projection_storage')
        );
        $showSendingReportsUseCase->execute(new ShowSendingReports\Command(), $this);
    }

    /**
     * @param $projections TemplateListProjection[]
     */
    public function templateListedSuccessfully(array $projections)
    {
        $this->templates = $projections;
    }

    /**
     * @param ThemeListProjection[] $projections
     */
    public function themeListedSuccessfully(array $projections)
    {
        $this->themes = $projections;
    }

    /**
     * @param $projections SendingReportsProjection[]
     */
    public function sendingReportsFetchedSuccessfully(array $projections)
    {
        $this->reports = $projections;
    }
}