<?php

namespace App\Repository;

use App\Entity\ContactMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactMetadata>
 *
 * @method ContactMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactMetadata[]    findAll()
 * @method ContactMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactMetadataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactMetadata::class);
    }

    //    /**
    //     * @return ContactMetadata[] Returns an array of ContactMetadata objects
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

    //    public function findOneBySomeField($value): ?ContactMetadata
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
