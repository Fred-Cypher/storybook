<?php

namespace App\Repository;

use App\Entity\Covers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Covers>
 *
 * @method Covers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Covers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Covers[]    findAll()
 * @method Covers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoversRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Covers::class);
    }

    //    /**
    //     * @return Covers[] Returns an array of Covers objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Covers
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
