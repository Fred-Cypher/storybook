<?php

namespace App\Repository;

use App\Entity\Drawings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Drawings>
 *
 * @method Drawings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Drawings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Drawings[]    findAll()
 * @method Drawings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrawingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drawings::class);
    }

    //    /**
    //     * @return Drawings[] Returns an array of Drawings objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Drawings
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
