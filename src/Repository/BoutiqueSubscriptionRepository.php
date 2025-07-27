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

    // Add custom query methods if needed
}