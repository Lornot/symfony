<?php

    namespace AppBundle\Controller;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\Config\Definition\Exception\Exception;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Validator\Constraints\File;

    class IdeasController extends Controller {

        public function listAction(Request $request) {

            $urls = [
                'babysitter_finding',
                'trucker_driving_supporting',
                'learning_app'
            ];

            $ideas = array_map(function($idea){
                return [
                    'href' => $this -> generateUrl('ideas_show', ['idea' => $idea]),
                    'text' => $idea
                ];
            }, $urls);

            $options = [
                'ideas' => $ideas
            ];

            return $this -> render('ideas/list.html.twig', $options);
        }

        /**
         * @Route("/ideas/{idea}", name="IdeaDescription")
         */
        public function showAction($idea){


            return $this -> render('ideas/idea.html.twig', [
                'idea_title' => $idea,
                'idea_subtitle' => '',
                'idea_description' => 'Great idea'
            ]);
        }



    }