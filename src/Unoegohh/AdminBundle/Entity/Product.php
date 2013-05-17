<?php

// src/Tutorial/BlogBundle/Entity/Post.php
namespace Unoegohh\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Product
{
    public function __construct()
    {
        $this->photo = new ArrayCollection();
    }
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
    protected $smallDesc;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $descr;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $price;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $discount;

    /**
     * @ORM\ManyToOne(targetEntity="Category", cascade={"all"})
     * @ORM\JoinColumn(name="category_id",referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Gallery", cascade={"all"})
     * @ORM\JoinColumn(name="photos", referencedColumnName="id")
     *
     */
    protected $photos;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $position;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $ringSize;

    public function __toString(){
        if($this->getName()){
            return $this->getName();
        }
        else{
            return "Продукт";
        }
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    public function getDesc()
    {
        return $this->desc;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function getDiscount()
    {
        return $this->discount;
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


    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setSmallDesc($smallDesc)
    {
        $this->smallDesc = $smallDesc;
    }

    public function getSmallDesc()
    {
        return $this->smallDesc;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPhotos($photos)
    {
        $this->photos = $photos;
    }

    public function getPhotos()
    {
        return $this->photos;
    }

    public function setDescr($descr)
    {
        $this->descr = $descr;
    }

    public function getDescr()
    {
        return $this->descr;
    }

    public function setRingSize($ringSize)
    {
        $this->ringSize = $ringSize;
    }

    public function getRingSize()
    {
        return $this->ringSize;
    }




}