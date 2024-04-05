<?php

namespace App\Repository;

use App\Entity\TrackingEventLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrackingEventLog>
 *
 * @method TrackingEventLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrackingEventLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrackingEventLog[]    findAll()
 * @method TrackingEventLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackingEventLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrackingEventLog::class);
    }

    //    /**
    //     * @return TrackingEventLog[] Returns an array of TrackingEventLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TrackingEventLog
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
