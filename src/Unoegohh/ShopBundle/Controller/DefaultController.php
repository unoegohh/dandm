<?php

namespace Unoegohh\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('UnoegohhAdminBundle:Product')->find($id);

        $medias = $product->getPhotos()->getGalleryHasMedias()->toArray();

        $media = get_class_methods($medias[0]->getMedia()->getProviderReference());
        return $this->render(':Shop:item.html.twig', array('product' => $product));
    }

    public function catalogAction($name, $page)
    {

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('UnoegohhAdminBundle:Category')->findOneBy(array('engName' => $name));

        if(!$category){
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }

        $offset = $page *12;

        //////////////////////////////////////////////////////////////////////////////////

        $template = ':Shop:catalogMain.html.twig';
        if($page != 0){
            $offset -= 2;
            $template = ':Shop:catalogCommon.html.twig';
        }
        $request = $this->getRequest();
        $order = $request->query->get('order');
        $orderArray = array('position' => 'ASC');
        if($order){
            if($order == 1){
                $orderType =  'ASC';
            }
            if($order == 2){
                $orderType =  'DESC';
            }
            $orderArray = array('price' => $orderType);
        }
        //////////////////////////////////////////////////////////////////////////////////
        $pricesRaw = $request->query->get('price');
        if($pricesRaw){
            $prices = explode( '-' , $pricesRaw);
            if($order){
                $query = $em->createQuery("select p from UnoegohhAdminBundle:Product p where p.price >= :min and p.price <= :max order by p.price " . $orderType);
            }else{
                $query = $em->createQuery("select p from UnoegohhAdminBundle:Product p where p.price >= :min and p.price <= :max order by p.position ASC");
            }

            $query->setParameters(array(
                'min' => $prices[0],
                'max' => $prices[1],
            ));
            $copyQuery = $query;


            $query->setMaxResults(12);
            $query->setFirstResult($offset);

            if($order){
                $query = $em->createQuery("select p from UnoegohhAdminBundle:Product p where p.price >= :min and p.price <= :max order by p.price " . $orderType);
            }else{
                $query = $em->createQuery("select p from UnoegohhAdminBundle:Product p where p.price >= :min and p.price <= :max order by p.position ASC");
            }
            $query->setParameters(array(
                'min' => $prices[0],
                'max' => $prices[1],
            ));
            $pageCount = ceil((count($query->getResult()) + 2) / 12);

            $products = $query->getResult();
        }else{

            $qb = $em->createQueryBuilder();
            $qb->select('count(product.id)')
            ->add('where', 'product.category = ?1');
            $qb->setParameter(1, $category->getId());
            $qb->from('UnoegohhAdminBundle:Product','product');

            $pageCount = ceil(($qb->getQuery()->getSingleScalarResult() + 2) / 12);
            $products = $em->getRepository('UnoegohhAdminBundle:Product')->findBy(array('category' => $category),$orderArray, 12 , $offset);
        }
        $link_addon = $request->server->get('QUERY_STRING');



        return $this->render(':Shop:catalog.html.twig', array(
            'link_addon' => $link_addon,
            'order' => $order,
            'prices' => $pricesRaw,
            'pageCount' => $pageCount,
            'page' => $page+1,
            'template' => $template,
            'category' => $category,
            'products' => $products
        ));
    }
    public function addToCartAction($id){
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $product = $em->getRepository('UnoegohhAdminBundle:Product')->find($id);
        $cart = $session->get('cart');
        if(isset($cart[$id])){
            $cart[$id]['count'] += 1;
        }else{
            $cart[$id]['item']= $product;
            $cart[$id]['count']= 1;
        }
        $session->set('cart', $cart);

        $stat = array();
        $stat['total'] = 0;
        $stat['count'] = 0;
        foreach($cart as $key=>$value){
            $item = $value['item']->getPrice();
            $stat['total'] += $item * $value['count'];
            $stat['count'] += $value['count'];
        }

        $response = new Response();
        $response->headers->set('Content-type', 'application/json');
        $response->setContent(json_encode($stat));
        return $response;
    }

    public function layoutCartAction(){

        $session = $this->get('session');
        $cart = $session->get('cart');
        $stat['total'] = 0;
        $stat['count'] = 0;
        if($cart){
            foreach($cart as $key=>$value){
                $item = $value['item']->getPrice();
                $stat['total'] += $item * $value['count'];
                $stat['count'] += $value['count'];
            }
        }
        return $this->render(':Shop:cartLayout.html.twig', array('cart' => $stat));

    }
    public function cartAction(){

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->get('session');
        $cart = $session->get('cart');
        if(!$cart){
            return $this->render(':Shop:cartEmpty.html.twig');
        }
        $cartIds = array();
        $total = 0;


        $newCart = $request->get('cart');
        if($newCart){
            foreach($newCart as $key=>&$value){
                $count = $value;
                $value = $cart[$key];
                $value['count'] = $count;
            }
            $cart = $newCart;
        }

        foreach($cart as $key => $cartItem){
            $cartIds[] = $key;
            $item = $cartItem['item'];
            $total += $cartItem['count'] * $item->getPrice();
        }
        $products = $em->getRepository('UnoegohhAdminBundle:Product')->findBy(array('id' => $cartIds));

        foreach($products as $product){
            $cart[$product->getId()]['item'] = $product;
        }
        $form = $this->createFormBuilder()
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('phone', 'text')
            ->add('town', 'text')
            ->add('address', 'text')
            ->add('comment', 'text')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);

            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $message = $this->get('mailer')->createMessage()
                ->setSubject('Заказ с сайта')
                ->setFrom(array($this->container->getParameter('mail_from') => $this->container->getParameter('mail_name')))
                ->setBody($this->renderView(':Shop:orderData.html.twig', array(
                'data' => $data,
                'cart' => $cart,
                'total' => $total
            )), 'text/html')
                ->addTo($this->container->getParameter('mail_from'));

            $this->get('mailer')->send($message);
            $session->set('cart', '');

            return $this->render(':Shop:orderSend.html.twig');
        }
        return $this->render(':Shop:cart.html.twig', array('cart' => $cart, 'total' => $total, 'form' => $form->createView()));

    }

    public function clearCartAction(){
        $session = $this->get('session');
        $session->set('cart', array());

        $response = new Response();
        $response->headers->set('Content-type', 'application/json');
        $response->setContent('ok');
        return $response;
    }

    public function removeCartAction($id){
        $session = $this->get('session');
        $cart = $session->get('cart');
        unset($cart[$id]);
        $session->set('cart', $cart);
        $response = new Response();
        $response->headers->set('Content-type', 'application/json');
        $response->setContent('ok');
        return $response;
    }

    public function catalogListAction(){
        $em = $this->getDoctrine()->getManager();
        $catalogs = $em->getRepository('UnoegohhAdminBundle:Category')->findAll();

        return $this->render(':Shop:catalogList.html.twig', array('catalogs' => $catalogs));

    }
}
