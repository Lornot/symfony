<?php

namespace IdeasApiBundle\Controller;

use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use FOS\RestBundle\Context\Context;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Entity\Idea;

class IdeasController extends FOSRestController
{

    /**
     * @return array
     *
     * @ApiDoc(
     *     statusCodes={
     *         200 = "Successful geting of ideas list"
     *     }
     * )
     */
    public function getAllAction()
    {
        $ideas_repository = $this->getDoctrine()->getRepository('AppBundle:Idea');

        $ideas = $ideas_repository->findBy(
            [],
            ['title' => 'ASC']
        );

        return $ideas;
    }

    public function getAction(int $idea_id)
    {
        $ideas_repository = $this->getDoctrine()->getRepository('AppBundle:Idea');
        $idea = $ideas_repository->find($idea_id);
        return $idea;
    }

    public function addAction($id)
    {
        $idea = new Idea();
        return $id;
    }

    public function updateAction($idea_id)
    {
        return $this->render('IdeasApiBundle:Idea:update.html.twig', array(
            // ...
        ));
    }

}
