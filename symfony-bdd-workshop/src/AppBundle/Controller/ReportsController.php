<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Traits\SanitizeHtml;
use Domain\Model\Mailing\Message;
use Domain\Model\Reports\MessageId;
use Domain\ReadModel\Projection\SendingReportsProjection;
use Domain\UseCase\ShowSendingReports;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportsController extends Controller implements ShowSendingReports\Responder
{
    use SanitizeHtml;

    private $response;

    public function listAction(Request $request)
    {
        $this->fetchReports($request);

        $paginator = $this->get('knp_paginator');
        $paginator->setDefaultPaginatorOptions([
            'filterFieldParameterName' => 'filter',
            'filterValueParameterName' => 'search',
        ]);
        $pagination = $paginator->paginate($this->response, $request->get('page', 1));

        return $this->render('AppBundle:Reports:list.html.twig', ['pagination' => $pagination]);
    }

    public function detailsAction($id)
    {
        $this->fetchSingleMessage($id);

        return $this->render('AppBundle:Reports:details.html.twig', ['message' => reset($this->response)]);
    }

    public function seeHtmlAction($id)
    {
        $this->fetchSingleMessage($id);
        /** @var Message $message */
        $message = reset($this->response);

        return new Response($this->stripScript($message->getHtml()));
    }

    /**
     * @param $projections SendingReportsProjection[]
     */
    public function sendingReportsFetchedSuccessfully(array $projections)
    {
        $this->response = $projections;
    }

    private function fetchSingleMessage($id)
    {
        $command = new ShowSendingReports\Command(new MessageId($id));

        $showSendingReportsUseCase = new ShowSendingReports(
            $this->get('projection_storage')
        );
        $showSendingReportsUseCase->execute($command, $this);
    }

    private function fetchReports(Request $request)
    {
        $command = $this->setUpCommand($request);

        $showSendingReportsUseCase = new ShowSendingReports(
            $this->get('projection_storage')
        );
        $showSendingReportsUseCase->execute($command, $this);
    }

    private function setUpCommand(Request $request)
    {
        $command = new ShowSendingReports\Command();

        if($request->get('filter') && $request->get('search')) {
            if(in_array($request->get('filter'), ['sender', 'recipient', 'subject'])) {
                $command->setFilterBy($request->get('filter'));
                $command->setSearchString($request->get('search'));
            }
        }

        if($request->get('date_from')) {
            $timestamp = strtotime($request->get('date_from'));
            if($timestamp !== false) {
                $command->setDateFrom($timestamp);
            }
        }

        if($request->get('date_to')) {
            $timestamp = strtotime($request->get('date_to'));
            if($timestamp !== false) {
                $command->setDateTo($timestamp);
            }
        }

        if($request->get('sort')) {
            if(in_array($request->get('sort'), ['recipient', 'sender', 'date', 'subject'])) {
                $command->setSortBy($request->get('sort'));
            }
        }

        if($request->get('order')) {
            if(in_array($request->get('order'), ['asc', 'desc'])) {
                $command->setSortOrder($request->get('order'));
            }
        }

        return $command;
    }
}
