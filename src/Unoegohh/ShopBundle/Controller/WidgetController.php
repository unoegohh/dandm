<?php

namespace Unoegohh\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class WidgetController extends Controller{

    public function indexAction($category = null){
        /*
         * @var $em EntityManager
         */

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('UnoegohhAdminBundle:Product');

        $items = $repo->getRandomItems();



        return $this->render(':Shop:widget.html.twig', array(
            'items' => $items
        ));
    }
}