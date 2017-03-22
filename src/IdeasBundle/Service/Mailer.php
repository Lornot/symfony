<?php

    namespace IdeasBundle\Service;

    class Mailer {

        public function __construct($type){

            echo "<pre>";
            print_r($type);
            echo "</pre>";

        }

        public function sendEmail() {

            $res = mail('marjawka92@gmail.com', 'test', 'Hello, wife, how are you?');

            return $res;
        }

    }