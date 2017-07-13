<?php

    namespace IdeasBundle\Entity;

    class Keyword
    {
        private $name;

        /**
         * @var integer
         */
        protected $id;

        /**
         * Get id
         *
         * @return integer
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set id
         *
         * @return integer
         */
        public function setId()
        {
            return $this->id;
        }

        public function getName()
        {
            return $this->name;
        }

        public function setName($name)
        {
            $this->name = $name;
        }
    }