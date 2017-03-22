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

            $manager = $this -> getDoctrine() -> getManager();
            $ideas = $manager -> getRepository('IdeasBundle:Idea') -> findLast5daysIdeas();

            $newsLetterManager = $this -> get('app.newsletter_manager');

            echo "<pre>";
            var_dump($newsLetterManager);
            echo "</pre>";

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

        public function updateAction(Request $request, $idea_id) {
            
            $idea = new Idea();
            $form = $this -> createForm(IdeaType::class, $idea);

            $form -> handleRequest($request);

            if ($form -> isSubmitted() && $form -> isValid()) {
                $idea = $form -> getData();
                $manager = $this -> getDoctrine() -> getManager();
                $idea_from_db = $manager -> getRepository('IdeasBundle:Idea') -> find($idea_id);

                $idea_from_db -> setTitle($idea -> getTitle());
                $idea_from_db -> setDescription($idea -> getDescription());
                $idea_from_db -> setCreatedAt($idea -> getCreatedAt());

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
                $form = $this -> createForm(IdeaType::class, $idea);
            }

            return $this -> render('ideas/update.html.twig', [
                'form' => $form -> createView()
            ]);
        }

        public function removeAction($idea_id) {

            $manager = $this -> getDoctrine() -> getManager();
            $idea = $manager -> getRepository('IdeasBundle:Idea') -> find($idea_id);
            if (!$idea) {
                throw $this -> createNotFoundException(
                    'No idea is found for id'.$idea_id
                );
            }

            $manager -> remove($idea);
            $manager -> flush();

            return $this -> redirectToRoute('ideas_list');
        }
    }