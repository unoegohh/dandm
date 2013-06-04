<?php

namespace Unoegohh\EbayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimpleXMLElement;
use Unoegohh\EbayBundle\Entity\RawItem;
use Unoegohh\EbayBundle\Form\ItemType;
use Unoegohh\EbayBundle\Entity\TranslationItem;
use Unoegohh\AdminBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends Controller
{
    public function itemAction(Request $request)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();



        $item = $em->getRepository('UnoegohhEbayBundle:TranslationItem')->findOneBy(array('translated' => false));
//        $item->setCurrTrans(true);
//
//        $rebro = $em->getRepository('UnoegohhEbayBundle:TranslationItem')->findBy(array('currTrans' => true));
//        foreach($rebro as $rebr){
//            $rebr->setCurrTrans(false);
//            $em->persist($rebr);
//            $em->flush();
//        }

        $em->persist($item);
        $em->flush();
        $form = $this->createForm(new ItemType(), $item);


        if ($request->isMethod('POST')) {
            $form->bind($request);
            $data = $form->getData();
            $data->setTranslated(true);
            $em->persist($data);
            $em->flush();

            /**
             * @var $rawItem RawItem
             */
            $rawItems = $em->getRepository('UnoegohhEbayBundle:RawItem')->findByIName($data->getEngText());

            foreach($rawItems as $rawItem){
                $product = new Product();
                $product->setPrice($rawItem->getPrice() * 32);
                $product->setPhotoUrl($rawItem->getLocalImg());
                $numbers = array();
                preg_match_all('/([0-9.\/]+ ?)+/',$rawItem->getName(), $numbers);
                $title = $data->getRuText();
                foreach($numbers[0] as $number){
                    $title = preg_replace('/\[\]/', $number, $title, 1);
                }
                $product->setName($title);
                $product->setCategory($data->getCategory());
                $product->setGoldType($data->GetGoldType());
                $product->setPosition(1);

                $em->persist($product);
                $em->flush();

            }



        }

        $qb = $em->createQueryBuilder();
        $qb->select('count(i.id)');
        $qb->where('i.translated = false');
        $qb->from('UnoegohhEbayBundle:TranslationItem','i');

        $countLeft = $qb->getQuery()->getSingleScalarResult();

        $qb->where('i.translated = true');

        $countDone = $qb->getQuery()->getSingleScalarResult();


        $item = $em->getRepository('UnoegohhEbayBundle:TranslationItem')->findOneBy(array('translated' => false));

        $examples = $em->getRepository('UnoegohhEbayBundle:RawItem')->findByIName($item->getEngText());
        $form = $this->createForm(new ItemType(), $item);



        return $this->render(':ItemAdmin:form.html.twig', array(
            'form' => $form->createView(),
            'left' => $countLeft,
            'done' => $countDone,
            'examples' => $examples
        ));
    }
}
