<?php

    namespace AppBundle\Service;

    class Mailer {
        protected $type;

        public function __construct($type){
            $this -> type = $type;
        }

        public function sendEmail() {

            $res = mail('marjawka92@gmail.com', 'test', 'Hello, wife, how are you?');

            return $res;
        }

    }