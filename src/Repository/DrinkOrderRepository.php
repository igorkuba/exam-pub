<?php

namespace App\Repository;

use App\Entity\DrinkOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DrinkOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrinkOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrinkOrder[]    findAll()
 * @method DrinkOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrinkOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrinkOrder::class);
    }
    
    public function save(DrinkOrder $order)
    {
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
    }
}
