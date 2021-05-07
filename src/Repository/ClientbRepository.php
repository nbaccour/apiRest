<?php

namespace App\Repository;

use App\Entity\Clientb;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Clientb|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clientb|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clientb[]    findAll()
 * @method Clientb[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientbRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clientb::class);
    }

    // /**
    //  * @return Clientb[] Returns an array of Clientb objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Clientb
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
