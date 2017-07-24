<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Keyword;

/**
 * Idea
 */
class Idea
{

    /**
     * @var integer
     */
    protected $id;

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
     *
     */
    protected $keywords;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    protected $created_at;

    /**
     * @var integer
     */
    private $attractiveness;

    /**
     * @var string
     * @Assert\File(mimeTypes={"image/jpeg","image/png"})
     *
     */
    private $image;


    public function __construct()
    {
        $this->keywords = new ArrayCollection();
    }

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
    

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this -> description;
    }

    public function setDescription($decription)
    {
        $this->description = $decription;
    }

    public function getCreatedAt()
    {
        return $this -> created_at;
    }

    public function setCreatedAt(\DateTime $created_at) {
        $this->created_at = $created_at;
    }

    public function addKeyword(Keyword $keyword)
    {
        $this->keywords->add($keyword);
    }

    public function removeKeyword(Keyword $keyword)
    {
        $this->keywords->removeElement($keyword);
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set attractiveness
     *
     * @param integer $attractiveness
     *
     * @return Idea
     */
    public function setAttractiveness($attractiveness)
    {
        $this->attractiveness = $attractiveness;
        return $this;
    }

    /**
     * Get attractiveness
     *
     * @return integer
     */
    public function getAttractiveness()
    {
        return $this->attractiveness;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Idea
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
