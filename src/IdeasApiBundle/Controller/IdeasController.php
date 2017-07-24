<?php

namespace IdeasApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use FOS\RestBundle\Controller\FOSRestController;

class IdeasController extends FOSRestController
{
    public function getAllAction()
    {
        $ideas_repository = $this->getDoctrine()->getRepository('AppBundle:Idea');

        $ideas = $ideas_repository->findBy(
            [],
            ['title' => 'ASC']
        );

        $view = $this->view($ideas, 200);

        return $this->handleView($view);

    }

    public function getAction($idea_id)
    {
        return $this->render('IdeasApiBundle:Idea:get.html.twig', array(
            // ...
        ));
    }

    public function addIdeaAction()
    {
        return $this->render('IdeasApiBundle:Idea:add.html.twig', array(
            // ...
        ));
    }

    public function updateAction($idea_id)
    {
        return $this->render('IdeasApiBundle:Idea:update.html.twig', array(
            // ...
        ));
    }

}
