<?php

// src/Tutorial/BlogBundle/Entity/Post.php
namespace Unoegohh\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Category
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
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    protected $engName;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $descr;

    /**
     * @var string $icon
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="icon", referencedColumnName="id")
     */
    protected $icon;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    protected $goldType;

    public function __toString(){
        if($this->getName()){
            return $this->getName();
        }
        else{
            return "Категория";
        }
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getIcon()
    {
        return $this->icon;
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

    public function setEngName($engName)
    {
        $this->engName = $engName;
    }

    public function getEngName()
    {
        return $this->engName;
    }

    public function setDescr($descr)
    {
        $this->descr = $descr;
    }

    public function getDescr()
    {
        return $this->descr;
    }

    public function setGoldType($goldType)
    {
        $this->goldType = $goldType;
    }

    public function getGoldType()
    {
        return $this->goldType;
    }
    public function getFullUrl()
    {
        return '/catalog/' . $this->getEngName();
    }
}