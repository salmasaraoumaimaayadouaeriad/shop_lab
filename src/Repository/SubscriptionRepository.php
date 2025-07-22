<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscription>
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function findActiveSubscriptionByCommercant($commercant): ?Subscription
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.commercant = :commercant')
            ->andWhere('s.statut = :statut')
            ->setParameter('commercant', $commercant)
            ->setParameter('statut', 'active')
            ->orderBy('s.dateCreation', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findExpiredSubscriptions(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.dateFin < :now')
            ->andWhere('s.statut = :statut')
            ->setParameter('now', new \DateTime())
            ->setParameter('statut', 'active')
            ->getQuery()
            ->getResult();
    }
}