<?php

// src/Tutorial/BlogBundle/Entity/Post.php
namespace Unoegohh\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class StaticPage
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
    protected $title;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     */
    protected $url;

    /**
     * @ORM\Column(type="text",nullable=true)
     *
     */
    protected $content;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     *
     */
    protected $widget;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    protected $enabled;

    public function __toString()
    {
        if($this->getTitle()){
            return $this->getTitle();
        }
        else{
            return "Статичная страница";
        }
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setWidget($widget)
    {
        $this->widget = $widget;
    }

    public function getWidget()
    {
        return $this->widget;
    }



}