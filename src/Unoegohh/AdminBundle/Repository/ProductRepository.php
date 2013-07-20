<?php
namespace Unoegohh\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function getRandomItems(){
        $items = $this->findAll();
        shuffle($items);


        return array_slice($items, 0 , 4);

    }
}