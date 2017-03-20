<?php

    namespace IdeasBundle\Controller;

    use IdeasBundle\Entity\Idea;
    use IdeasBundle\Form\IdeaType;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class IdeasController extends Controller {

        public function homeAction() {
            return $this -> render('ideas/home.html.twig');
        }

        public function listAction(Request $request) {

            $ideas_repository = $this -> getDoctrine() -> getRepository('IdeasBundle:Idea');

            $ideas = $ideas_repository -> findBy(
                [],
                ['title' => 'ASC']
            );

            $options = [
                'ideas' => $ideas,
            ];

            return $this -> render('ideas/list.html.twig', $options);
        }

        public function showAction($idea){

            $repository = $this -> getDoctrine() -> getRepository('IdeasBundle:Idea');

            $idea = $repository -> find($idea);

            if (!$idea) {
                throw $this -> createNotFoundException(
                    'No idea found for id '.$idea
                );
            }

            return $this -> render('ideas/idea.html.twig', [
                'idea_title' => $idea -> getTitle(),
                'idea_description' => $idea -> getDescription(),
                'idea_created_at' => $idea -> getCreatedAt() -> format('d.m.Y'),
                'idea_id' => $idea -> getId()
            ]);
        }

        public function addAction(Request $request) {
            $idea = new Idea();
            $idea -> setCreatedAt(new \DateTime());

            $form = $this -> createForm(IdeaType::class, $idea);

            $form -> handleRequest($request);

            if ($form -> isSubmitted() && $form -> isValid()) {
                $idea = $form -> getData();

                /** Апдейт бази даних*/
                $em = $this -> getDoctrine() -> getManager();
                $em -> persist($idea);
                $em -> flush();

                $this -> addFlash('notice', 'Success');
                return $this -> redirectToRoute('ideas_list');
            }

            return $this -> render('ideas/add.html.twig', [
                'form' => $form -> createView()
            ]);
        }

        public function updateAction($idea_id, Request $request) {
            
            $idea = new Idea();
            $form = $this -> createForm(IdeaType::class, $idea);
            $form -> handleRequest($request);
            if ($form -> isSubmitted() && $form -> isValid()) {
                $idea = $form -> getData();
                $manager = $this -> getDoctrine() -> getManager();
                $manager -> persist($idea);
                $manager -> flush();
                return $this -> redirectToRoute('ideas_list');
            } else {
                $repository = $this -> getDoctrine() -> getRepository('IdeasBundle:Idea');
                $idea = $repository -> find($idea_id);
                if(!$idea) {
                    throw $this -> createNotFoundException(
                        'No idea is found for id '.$idea
                    );
                }
            }

            return $this -> render('ideas/update.html.twig', [
                'form' => $form -> createView()
            ]);
        }
    }