<?php

    namespace IdeasBundle\Service;

    use IdeasBundle\Service\Mailer;

    class NewsLetterManager
    {
        protected $mailer;

        public function __construct(Mailer $mailer){
            $this -> mailer = $mailer;
        }
    }