<?php

namespace Unoegohh\EbayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimpleXMLElement;
use Unoegohh\EbayBundle\Entity\RawItem;
use Unoegohh\EbayBundle\Entity\TranslationItem;
use Doctrine\ORM\EntityManager;
use Unoegohh\AdminBundle\Entity\Product;

class DefaultController extends Controller
{
    public function indexAction()
    {

        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $platform   = $connection->getDatabasePlatform();

        //todo delete only ebay items

        $connection->executeUpdate($platform->getTruncateTableSQL('RawItem', true /* whether to cascade */));
        $connection->executeUpdate($platform->getTruncateTableSQL('Product', true /* whether to cascade */));

        $currentPage = 0;
        $count = 0;
        $newItemsCount = 0;
        $k = 1;
        while($currentPage == 0){

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
                "&paginationInput.entriesPerPage=100&paginationInput.pageNumber=$k".
                "&outputSelector=$outputSelector"
                ;

            $find = file_get_contents($url);
            $result = new SimpleXMLElement($find);

            $k++;


            $output = array();
            $titles = array();
            if(count($result->searchResult->item) < 100){
                $currentPage = 1;
            }
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
                $search['translated'] = true;
                $result = $em->getRepository('UnoegohhEbayBundle:TranslationItem')->findOneBy($search);
                if($result){
                    $product = new Product();
                    $product->getEbay(true);
                    $product->setPrice(ceil(($newItem->getPrice() * 32 + $newItem->getPrice() * 32 * $this->container->getParameter('marja')/100)/100) * 100);
                    $product->setPhotoUrl($newItem->getLocalImg());
                    $numbers = array();
                    preg_match_all('/([0-9.\/]+ ?)+/',$newItem->getName(), $numbers);
                    $title = $result->getRuText();
                    foreach($numbers[0] as $number){
                        $title = preg_replace('/\[\]/', $number, $title, 1);
                    }
                    $product->setName($title);
                    $product->setCategory($result->getCategory());
                    $product->setGoldType($result->GetGoldType());
                    $product->setPosition(1);

                    $em->persist($product);
                    $em->flush();
                }
            }

        }
        $response = new Response('Total parsed ' . $count . '<br>' . 'Added new titles ' . $newItemsCount);
        return $response;
    }
}
