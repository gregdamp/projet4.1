<?php

namespace App\Repository;

use App\Entity\Billets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method Billets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Billets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Billets[]    findAll()
 * @method Billets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BilletsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Billets::class);
    }

    // /**
    //  * @return Billets[] Returns an array of Billets objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Billets
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function TicketsByDate($date)
    {
        return $this->createQueryBuilder('billets')
            ->andWhere('billets.date = :date')
            ->setParameter('date', $date)
            ->select('SUM(billets.quantite) as total')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    public function SoldOutDate()
    {
        return $this->createQueryBuilder('billets')
            ->select('billets.date', 'SUM(billets.quantite) as total')
            ->groupBy('billets.date')
            ->having('SUM(billets.quantite) >= 1000')
            ->getQuery()
            ->getResult();
    }

}


