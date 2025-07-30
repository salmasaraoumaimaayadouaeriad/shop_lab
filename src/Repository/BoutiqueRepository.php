<?php

namespace App\Repository;

use App\Entity\Boutique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Boutique>
 */
class BoutiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Boutique::class);
    }

    /**
     * Find boutiques by status for admin
     */
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.statut = :status')
            ->setParameter('status', $status)
            ->orderBy('b.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find flagged or non-compliant boutiques
     */
    public function findFlagged(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.statut IN (:statuses)')
            ->setParameter('statuses', ['signale', 'non_conforme', 'suspendu'])
            ->orderBy('b.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find boutique statistics for admin dashboard
     */
    public function findStatistics(): array
    {
        $qb = $this->createQueryBuilder('b');
        
        return [
            'total' => $qb->select('COUNT(b.id)')->getQuery()->getSingleScalarResult(),
            'active' => $qb->select('COUNT(b.id)')->andWhere('b.statut = :status')->setParameter('status', 'actif')->getQuery()->getSingleScalarResult(),
            'pending' => $qb->select('COUNT(b.id)')->andWhere('b.statut = :status')->setParameter('status', 'en_cours')->getQuery()->getSingleScalarResult(),
            'flagged' => $qb->select('COUNT(b.id)')->andWhere('b.statut IN (:statuses)')->setParameter('statuses', ['signale', 'non_conforme'])->getQuery()->getSingleScalarResult(),
        ];
    }

    /**
     * Find most active boutiques based on number of visits and status
     */
    public function findMostActive(int $limit = 10): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.statut = :status')
            ->setParameter('status', 'actif')
            ->orderBy('b.visites', 'DESC')
            ->addOrderBy('b.dateCreation', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search boutiques for admin
     */
    public function searchForAdmin(string $query): array
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.commercant', 'c')
            ->andWhere('b.nom LIKE :query OR b.description LIKE :query OR c.nom LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('b.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find boutiques by template
     */
    public function findByTemplate(string $template): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.template = :template')
            ->setParameter('template', $template)
            ->orderBy('b.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
