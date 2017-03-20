<?php

namespace IdeasBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Idea
 */
class Idea
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @Assert\NotBlank()
     */
    protected $idea;

    /**
     * @Assert\NotBlank()
     * 
     */
    protected $title;

    /**
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    protected $created_at;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this -> id;
    }

    /**
     * Set id
     *
     * @return integer
     */
    public function setId()
    {
        return $this ->id;
    }

    public function getIdea() {
        return $this -> idea;
    }

    public function setIdea($idea) {
        $this -> idea = $idea;
    }

    public function getTitle() {
        return $this -> title;
    }

    public function setTitle($title) {
        $this -> title = $title;
    }

    public function getDescription() {
        return $this -> description;
    }

    public function setDescription($decription) {
        $this -> description = $decription;
    }

    public function getCreatedAt(){
        return $this -> created_at;
    }

    public function setCreatedAt(\DateTime $created_at) {
        $this -> created_at = $created_at;
    }

}
