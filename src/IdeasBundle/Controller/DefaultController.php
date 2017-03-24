<?php

    namespace IdeasBundle\Controller;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Response;

    class DefaultController extends Controller
    {

        public function adminAction() {
            return new Response('<html><body>Admin page!</body></html>');
        }


    }