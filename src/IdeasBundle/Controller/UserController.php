<?php

namespace IdeasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function showAction($user_id)
    {
        return $this->render('IdeasBundle:users:show.html.twig', array(
            // ...
        ));
    }

    public function listAction()
    {
        $user = new User();

        $user->setEmail('dd');
        $user->setFirstName('Oleg');
        $user->setPassword('Oleg');


        $validator = $this->get('validator');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }

        return new Response('The user is valid!!');

        return $this->render('IdeasBundle:users:list.html.twig', array(
            // ...
        ));
    }

}
