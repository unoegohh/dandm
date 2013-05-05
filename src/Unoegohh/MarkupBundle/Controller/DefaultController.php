<?php

namespace Unoegohh\MarkupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render(':Markup:catalog.html.twig');
    }

    public function formAction()
    {
        return $this->render(':Markup:form.html.twig');
    }

    public function cartAction()
    {
        return $this->render(':Markup:cart.html.twig');
    }

    public function categoryAction()
    {
        return $this->render(':Markup:category.html.twig');
    }

    public function itemAction()
    {
        return $this->render(':Markup:item.html.twig');
    }
}
