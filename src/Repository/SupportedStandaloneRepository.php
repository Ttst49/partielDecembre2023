<?php

namespace App\Repository;

use App\Entity\SupportedStandalone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SupportedStandalone>
 *
 * @method SupportedStandalone|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupportedStandalone|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupportedStandalone[]    findAll()
 * @method SupportedStandalone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupportedStandaloneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupportedStandalone::class);
    }

//    /**
//     * @return SupportedStandalone[] Returns an array of SupportedStandalone objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SupportedStandalone
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
