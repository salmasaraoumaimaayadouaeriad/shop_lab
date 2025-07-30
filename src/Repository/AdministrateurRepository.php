<?php

namespace App\Repository;

use App\Entity\Administrateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Administrateur>
 */
class AdministrateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Administrateur::class);
    }

    //    /**
    //     * @return Administrateur[] Returns an array of Administrateur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Administrateur
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Find admin statistics including count by niveau and total count
     * @return array Returns an array of admin statistics
     */
    public function findStatistics(): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.niveau, COUNT(a.id) as count')
            ->groupBy('a.niveau');

        $byNiveau = $qb->getQuery()->getResult();

        $totalQb = $this->createQueryBuilder('a')
            ->select('COUNT(a.id) as total');

        $total = $totalQb->getQuery()->getSingleScalarResult();

        return [
            'byNiveau' => $byNiveau,
            'total' => $total
        ];
    }
}
