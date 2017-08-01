<?php

namespace IdeasApiBundle\Controller;

use AppBundle\AppBundle;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Context\Context;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Entity\Idea;
use AppBundle\Entity\Keyword;
use AppBundle\Form\IdeaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function addAction(Request $request)
    {
        $form = $this->createForm(IdeaType::class, null, [
            'csrf_protection' => false
        ]);

        $data = $request->request->all();
        $form->submit($data);

        if (!$form->isValid()) {
            return $form;
        }
        $idea = $form->getData();
        $manager = $this->getDoctrine()->getManager();

        if (isset($data['keywords'])) {
            foreach ($data['keywords'] as $keyword) {
                $keywordObject = new Keyword();
                $keywordObject->setName($keyword['text']);
                $keywordObject->setIdea($idea);
                $manager->persist($keywordObject);
            }
        }

        $manager->persist($idea);
        $manager->flush();

        $routeOptions = [
            'idea_id' => $idea->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('get_idea', $routeOptions, Response::HTTP_CREATED);
    }

    public function updateAction(Request $request, $idea_id)
    {
        $manager = $this->getDoctrine()->getManager();
        $ideas_repository = $this->getDoctrine()->getRepository('AppBundle:Idea');
        $data = $request->request->all();

        $current_idea = $ideas_repository->find($idea_id);

        if ($data) {
            $form = $this->createForm(IdeaType::class, null, [
                'csrf_protection' => false
            ]);

            //return $data;

            /*$file = $data->getImage();
            if ($file) {
                $fileUploader = $this->get('app.file_uploader');
                $fileName = $fileUploader->upload($file);
                $current_idea->setImage($fileName);
            }*/

            $form->submit($data);

            if (!$form->isValid()) {
                return $form;
            }

            $current_keywords = $current_idea->getKeywords();

            foreach ($data['keywords'] as $keyword) {
                if (!isset($keyword['id'])) {
                    $keywordObject = new Keyword();
                    $keywordObject->setName($keyword['name']);
                    $keywordObject->setIdea($current_idea);
                    $manager->persist($keywordObject);
                }
            }

            $new_keywords_ids = array_column($data['keywords'], 'id');

            /** remove current keyword if it is not in the list of keyword from client */
            foreach ($current_keywords as $current_keyword) {
                if (!in_array($current_keyword->getId(), $new_keywords_ids)) {
                    $keyword = $manager->find(Keyword::class, $current_keyword->getId());
                    $manager->remove($keyword);
                    $manager->flush();
                }
            }

            /** @todo refactor me, please */
            $current_idea->setTitle($data['title']);
            $current_idea->setDescription($data['description']);
            if (isset($data['attractiveness']))
            $current_idea->setAttractiveness($data['attractiveness']);

            $manager->persist($current_idea);
            $manager->flush();

        }
    }

    public function removeAction($idea_id)
    {
        $manager = $this->getDoctrine()->getManager();
        $idea = $manager->getRepository('AppBundle:Idea')->find($idea_id);
        if (!$idea) {
            throw $this->createNotFoundException(
                'No idea is found for id'.$idea_id
            );
        }

        $manager->remove($idea);
        $manager->flush();

        return new View(null, Response::HTTP_NO_CONTENT);
    }



}
