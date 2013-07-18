<?php

// src/Tutorial/BlogBundle/Entity/Post.php
namespace Unoegohh\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $url;

    /**
     * @ORM\Column(type="string",nullable=true)
     *
     */
    protected $position;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     *
     */
    protected $red_link;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", cascade={"all"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;
//
    public function __toString(){
        if($this->getName()){
            return (string) $this->getName();
        }
        else{
            return "Элемент меню";
        }
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setRedLink($red_link)
    {
        $this->red_link = $red_link;
    }

    public function getRedLink()
    {
        return $this->red_link;
    }


}