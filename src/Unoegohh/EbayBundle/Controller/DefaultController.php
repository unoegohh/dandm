<?php

namespace Unoegohh\EbayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimpleXMLElement;
use Unoegohh\EbayBundle\Entity\RawItem;
use Unoegohh\EbayBundle\Entity\TranslationItem;
use Doctrine\ORM\EntityManager;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $outputSelector = 'PictureURLSuperSize';

        $url = "http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsAdvanced" .
            "&SERVICE-VERSION=1.11.0" .
            "&SECURITY-APPNAME=DandMe38d-69bb-4a41-aeb2-159622d4095" .
            "&RESPONSE-DATA-FORMAT=XML" .
            "&REST-PAYLOAD" .
            "&itemFilter(0).name=Seller" .
            "&itemFilter(0).value=loose.diamonds.king" .
            "&itemFilter(1).name=LocatedIn" .
            "&itemFilter(1).value=WorldWide".
//            "&paginationInput.entriesPerPage=15&paginationInput.pageNumber=1".
            "&outputSelector=$outputSelector"
            ;

        $find = file_get_contents($url);
        $result = new SimpleXMLElement($find);


        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('RawItem', true /* whether to cascade */));

        $count = 0;
        $newItemsCount = 0;
        $output = array();
        $titles = array();
        foreach($result->searchResult->item as $item){


            $newItem = new RawItem();
            $newItem->setName((string)$item->title);
            $newItem->setIName(preg_replace('/([0-9.]+ ?)+/', '[] ', $item->title));
            $newItem->setItemId($item->itemId);
            $newItem->setImage($item->pictureURLSuperSize);
            $newItem->setPrice($item->sellingStatus->currentPrice);


            $img_url = '/uploads/'. md5($item->pictureURLSuperSize) . '.png';
            $newItem->setLocalImg($img_url);
            $em->persist($newItem);
            $em->flush();
            $count++;


            $search = array();
            $transItem = new TranslationItem();

            $ch = curl_init($item->pictureURLSuperSize);
            $fp = fopen($this->get('kernel')->getRootDir() . '/../web' . $img_url, 'ab+');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            $search['engText'] = $newItem->getIName();
            $result = $em->getRepository('UnoegohhEbayBundle:TranslationItem')->findBy($search);



            if(!count($result)){
                $transItem->setEngText($newItem->getIName());
                $transItem->setTranslated(false);
                $transItem->setCurrTrans(false);
                $em->persist($transItem);
                $em->flush();
                $newItemsCount++;
            }
        }

        $response = new Response('Total parsed ' . $count . '<br>' . 'Added new titles ' . $newItemsCount);
        return $response;
    }
}
