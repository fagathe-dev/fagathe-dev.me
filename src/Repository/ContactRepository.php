<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * @param array $criteria
     * 
     * @return Contact[] Returns an array of Contact objects
     */
    public function filter(array $criteria = []): array
    {
        $qb = $this->createQueryBuilder('c');

        if (array_key_exists('state', $criteria) && $criteria['state'] !== '') {
            $qb->andWhere('c.state = :state')
                ->setParameter('state', $criteria['state']);
        }

        if (array_key_exists('subject', $criteria) && $criteria['subject'] !== '') {
            $qb->andWhere('c.subject = :subject')
                ->setParameter('subject', $criteria['subject']);
        }

        return $qb
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Contact
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
