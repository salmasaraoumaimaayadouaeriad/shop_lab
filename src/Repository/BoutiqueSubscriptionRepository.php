<?php

namespace App\Repository;

use App\Entity\BoutiqueSubscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BoutiqueSubscription>
 *
 * @method BoutiqueSubscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoutiqueSubscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoutiqueSubscription[]    findAll()
 * @method BoutiqueSubscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoutiqueSubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoutiqueSubscription::class);
    }

    /**
     * Find subscription statistics for admin dashboard
     */
    public function findStatistics(): array
    {
        $qb = $this->createQueryBuilder('bs');
        
        // Get total active subscriptions
        $activeTotal = $qb->select('COUNT(bs)')
            ->where('bs.statut = :active')
            ->setParameter('active', 'active')
            ->getQuery()
            ->getSingleScalarResult();

        // Get subscriptions by plan type
        $byType = $this->createQueryBuilder('bs')
            ->select('bs.plan, COUNT(bs) as count')
            ->groupBy('bs.plan')
            ->getQuery()
            ->getResult();

        // Get total revenue
        $revenue = $this->createQueryBuilder('bs')
            ->select('SUM(bs.prix)')
            ->where('bs.statut = :active')
            ->setParameter('active', 'active')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'totalActive' => $activeTotal,
            'byType' => $byType,
            'totalRevenue' => $revenue ?? 0
        ];
    }
}