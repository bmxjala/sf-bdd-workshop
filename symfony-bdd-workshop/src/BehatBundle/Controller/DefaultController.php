<?php

namespace BehatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $banners = $this->get('behatbanner.repository.banner_repository')->findAll();

        return $this->render(
            'BehatBundle:Default:index.html.twig',
            ['banners' => $banners]
        );
    }
}
