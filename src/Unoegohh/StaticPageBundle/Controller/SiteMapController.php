<?php

namespace Unoegohh\StaticPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;

class SiteMapController extends Controller
{
    public function indexAction()
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $staticPage = $em->getRepository('UnoegohhAdminBundle:StaticPage')->findBy(array('enabled' => false));
        $catalogs = $em->getRepository('UnoegohhAdminBundle:Category')->findAll();
        return $this->render(':Pages:SiteMap.html.twig', array(
            'staticPage' => $staticPage,
            'catalogs' => $catalogs
        ));
    }


}
