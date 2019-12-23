<?php

namespace App\Repository;

use App\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OrderItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderItem[]    findAll()
 * @method OrderItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }
    
    public function itemsByProduct($product)
    {
        $q= $this->createQueryBuilder('q');
        $q->andWhere('q.product=:product');
        $q->setParameter('product', $product);
        $q->join('q.drinkOrder', 'order');
        $q->orderBy('order.date','desc');
        return $q->getQuery();
    }
    
    public function sumByProduct($product)
    {
        $q= $this->createQueryBuilder('q');
        $q->andWhere('q.product=:product');
        $q->setParameter('product', $product);
        $q->join('q.drinkOrder', 'order');
        $q->orderBy('order.date','desc');
        $q->addSelect('sum(q.price) as sum');
        $q->addSelect('count(q.price) as number');
        return $q->getQuery()->getResult()[0];
    }
    
    public function itemsByCustomer($customer)
    {
        $q= $this->createQueryBuilder('q');
        $q->join('q.drinkOrder', 'ord');
        $q->orderBy('ord.date','desc');
        $q->andWhere('ord.customer=:customer');
        $q->setParameter('customer', $customer);
        return $q->getQuery();
    }
    
    public function sumByCustomer($customer)
    {
        $q= $this->createQueryBuilder('q');
        $q->join('q.drinkOrder', 'ord');
        $q->orderBy('ord.date','desc');
        $q->andWhere('ord.customer=:customer');
        $q->setParameter('customer', $customer);
        $q->addSelect('sum(q.price) as sum');
        $q->addSelect('count(q.price) as number');
        return $q->getQuery()->getResult()[0];
    }
    
    public function itemsPerDay()
    {
        $yesterday=new \DateTime();
        $yesterday->modify('- 24 hours');
        $q= $this->createQueryBuilder('q');
        $q->join('q.drinkOrder', 'ord');
        $q->orderBy('ord.date','desc');
        $q->andWhere('ord.date>=:date');
        $q->setParameter('date', $yesterday);
        return $q->getQuery();
    }
    
    public function sumPerDay()
    {
        $yesterday=new \DateTime();
        $yesterday->modify('- 24 hours');
        $q= $this->createQueryBuilder('q');
        $q->join('q.drinkOrder', 'ord');
        $q->orderBy('ord.date','desc');
        $q->andWhere('ord.date>=:date');
        $q->setParameter('date', $yesterday);
        $q->addSelect('sum(q.price) as sum');
        $q->addSelect('count(q.price) as number');
        return $q->getQuery()->getResult()[0];
    }
}
