<?php

    namespace IdeasBundle\Controller;

    use IdeasBundle\Entity\Idea;
    use IdeasBundle\Form\IdeaType;
    use IdeasBundle\Service\FileUploader;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\File\File;

    //use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

    class IdeasController extends Controller {

        /*private $file_uploader;

        public function __construct(FileUploader $fileUploader)
        {
            $this->file_uploader = $fileUploader;
        }*/

        public function overviewAction() {

            $logger = $this->get('logger');

            $logger->info('The idea was deleted');
            //$logger->error('An error occured');

            //$logger->critical('Delete has been occured with error', ['cause' => 'in_hurry']);

            $ideas_repository = $this->getDoctrine()->getRepository('IdeasBundle:Idea');
            $ideas = $ideas_repository->findBy(
                [],
                ['title' => 'ASC']
            );

            return $this->render('IdeasBundle:Default:overview.html.twig', [
                'ideas' => $ideas
            ]);
        }

        public function listAction(Request $request) {

            if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
                throw $this->createAccessDeniedException();

            $user = $this->getUser();

            $ideas_repository = $this->getDoctrine()->getRepository('IdeasBundle:Idea');

            $ideas = $ideas_repository->findBy(
                [],
                ['title' => 'ASC']
            );

            $manager = $this->getDoctrine()->getManager();
            $ideas = $manager->getRepository('IdeasBundle:Idea')->findLast5daysIdeas();

            $newsLetterManager = $this->get('app.newsletter_manager');

            $options = [
                'ideas' => $ideas,
            ];

            return $this->render('ideas/list.html.twig', $options);
        }

        public function showAction($idea){

            $repository = $this->getDoctrine()->getRepository('IdeasBundle:Idea');
            $idea = $repository->find($idea);
            if (!$idea) {
                throw $this->createNotFoundException(
                    'No idea found for id '.$idea
                );
            }
            return $this->render('IdeasBundle:Default:idea.html.twig', [
                'idea_title' => $idea->getTitle(),
                'idea_description' => $idea->getDescription(),
                'idea_created_at' => $idea->getCreatedAt()->format('d.m.Y'),
                'idea_id' => $idea->getId(),
                'idea'    => $idea
            ]);
        }

        /**
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
         * Security("has_role('ROLE_ADMIN')")
         */
        public function addAction(Request $request/*, FileUploader $fileUploader*/) {

            //$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page');

            $idea = new Idea();
            $idea->setCreatedAt(new \DateTime());
            $form = $this->createForm(IdeaType::class, $idea);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $idea = $form->getData();

                $fileUploader = $this->get('app.file_uploader');
                $file = $idea->getImage();
                $fileName = $fileUploader->upload($file);
                $idea->setImage($fileName);

                /** Апдейт бази даних*/
                $em = $this->getDoctrine()->getManager();
                $em->persist($idea);
                $em->flush();

                /** Посилання емейлу */
                $message = \Swift_Message::newInstance()
                   ->setSubject('New idea')
                   ->setFrom('ideas@gmail.com')
                   ->setTo('veishen19@gmail.com')
                   ->setBody(
                        $this->renderView(
                            'Emails/new_idea_message.html.twig',
                            [
                                'name' => 'Oleg',
                                'title' => $idea->getTitle(),
                                'description' => $idea->getTitle()
                            ]
                        ),
                        'text/html'
                    );

                $this->get('mailer')->send($message);

                $this->addFlash('notice', 'Success');
                return $this->redirectToRoute('overview');
            }

            return $this->render('IdeasBundle:Default:add.html.twig', [
                'form' => $form->createView()
            ]);
        }

        public function updateAction(Request $request, $idea_id) {
            
            $idea = new Idea();
            $form = $this->createForm(IdeaType::class, $idea);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {

                $idea = $form->getData();

                $manager = $this->getDoctrine()->getManager();
                $idea_from_db = $manager->getRepository('IdeasBundle:Idea')->find($idea_id);

                $fileUploader = $this->get('app.file_uploader');
                $file = $idea->getImage();
                $fileName = $fileUploader->upload($file);

                $idea_from_db->setTitle($idea->getTitle());
                $idea_from_db->setDescription($idea->getDescription());
                $idea_from_db->setCreatedAt($idea->getCreatedAt());
                $idea_from_db->setImage($fileName);

                $manager->flush();
                return $this->redirectToRoute('overview');
            } else {
                $repository = $this->getDoctrine()->getRepository('IdeasBundle:Idea');
                $idea = $repository->find($idea_id);

                $image_name = $idea->getImage();
                if ($image_name) {
                    $idea->setImage(
                        new File($this->getParameter('ideas_images').'/'.$idea->getImage())
                    );
                }

                if(!$idea) {
                    throw $this->createNotFoundException(
                        'No idea is found for id '.$idea
                    );
                }
                $form = $this->createForm(IdeaType::class, $idea);
            }

            return $this->render('IdeasBundle:Default:update.html.twig', [
                'form' => $form->createView()
            ]);
        }

        public function removeAction($idea_id) {

            $manager = $this->getDoctrine()->getManager();
            $idea = $manager->getRepository('IdeasBundle:Idea')->find($idea_id);
            if (!$idea) {
                throw $this->createNotFoundException(
                    'No idea is found for id'.$idea_id
                );
            }

            $manager->remove($idea);
            $logger = $this->get('logger');
            $manager->flush();
            return $this->redirectToRoute('overview');
        }

        public function aboutAction()
        {
            return $this->render('IdeasBundle:Default:about.html.twig');
        }
    }