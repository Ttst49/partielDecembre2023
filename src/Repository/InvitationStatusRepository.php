<?php

namespace App\Repository;

use App\Entity\InvitationStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InvitationStatus>
 *
 * @method InvitationStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvitationStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvitationStatus[]    findAll()
 * @method InvitationStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitationStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvitationStatus::class);
    }

//    /**
//     * @return InvitationStatus[] Returns an array of InvitationStatus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InvitationStatus
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
