<?php

namespace Unoegohh\StaticPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $staticPage = $em->getRepository('UnoegohhAdminBundle:StaticPage')->findOneBy(array('url' => 'main'));
        return $this->render(':Pages:staticPage.html.twig', array('staticPage' => $staticPage));
    }

    public function pageAction($url)
    {
        $em = $this->getDoctrine()->getManager();
        $staticPage = $em->getRepository('UnoegohhAdminBundle:StaticPage')->findOneBy(array('url' => $url, 'enabled' => false));

        if(!$staticPage){
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }
        return $this->render(':Pages:staticPage.html.twig', array('staticPage' => $staticPage));
    }

    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('UnoegohhAdminBundle:Menu')->findAll();

        $menuRender = array();
        foreach($menu as $item){
            if(!$item->getParent()){
                $menuRender[$item->getId()]['position'] = $item->getPosition();
                $menuRender[$item->getId()]['red_link'] = $item->getRedLink();
                $menuRender[$item->getId()]['item'] = $item;
            }
        }
        $this->aasort($menuRender,"position");

        foreach($menu as $item){
            if($item->getParent()){
                $menuRender[$item->getParent()->getId()]['child'][$item->getId()]['position'] = $item->getPosition();
                $menuRender[$item->getParent()->getId()]['child'][$item->getId()]['item'] = $item;
                $this->aasort($menuRender[$item->getParent()->getId()]['child'],"position");
            }
        }

        return $this->render('::menu.html.twig', array('menuRender' => $menuRender));
    }

    public function aasort (&$array, $key) {
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        $array=$ret;
    }

}
