<?php

    namespace IdeasBundle\Controller;

    use IdeasBundle\Entity\Idea;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\Config\Definition\Exception\Exception;
    use Symfony\Component\Form\Extension\Core\Type\CountryType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;

    class IdeasController extends Controller {

        public function homeAction() {
            return $this -> render('ideas/home.html.twig');
        }

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

            $idea = new Idea();
            $idea -> setIdea('Portal for home masters');
            $idea -> setTitle('masterok');
            $idea -> setDescription('Home master can get the work');
            $idea -> setCreatedAt(new \DateTime());

            $form = $this -> createFormBuilder($idea)
                -> add('idea', TextType::class)
                -> add('title', TextType::class)
                -> add('description', TextType::class)
                -> add('created_at', DateType::class)
                -> add('countries', CountryType::class, ['label' => 'Country'])
                -> add('save', SubmitType::class, ['label' => 'Create idea'])
                -> getForm();

            $form -> handleRequest($request);

            if ($form -> isSubmitted() && $form -> isValid()) {
                $idea = $form -> getData();
                
            }

            $options = [
                'ideas' => $ideas,
                'form'  => $form -> createView()
            ];

            return $this -> render('ideas/list.html.twig', $options);
        }

        public function showAction($idea){

            return $this -> render('ideas/idea.html.twig', [
                'idea_title' => $idea,
                'idea_subtitle' => '',
                'idea_description' => 'Great idea',
                'idea_image' => 'images/'.$idea.'.jpg'
            ]);
        }

        public function addAction(Request $request) {
            $idea = new Idea();
            $idea -> setIdea('Portal for home masters');
            $idea -> setTitle('masterok');
            $idea -> setDescription('Home master can get the work');
            $idea -> setCreatedAt(new \DateTime());

            $form = $this -> createFormBuilder($idea)
                -> add('idea', TextType::class)
                -> add('title', TextType::class)
                -> add('description', TextareaType::class)
                -> add('created_at', DateType::class)
                -> add('save', SubmitType::class, ['label' => 'Create idea'])
                -> getForm();

            $form -> handleRequest($request);

            if ($form -> isSubmitted() && $form -> isValid()) {
                $idea = $form -> getData();

                $this -> addFlash('notice', 'Success');
                return $this -> redirectToRoute('ideas_list');
            }

            return $this -> render('ideas/add.html.twig', [
                'form' => $form -> createView()
            ]);
        }

    }