<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
     * Find user statistics for admin dashboard
     */
    public function findStatistics(): array
    {
        $qb = $this->createQueryBuilder('u');
        $totalUsers = $qb->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult();

        $qb = $this->createQueryBuilder('u');
        $totalActive = $qb->select('COUNT(u)')
            ->where('u.isVerified = :verified')
            ->setParameter('verified', true)
            ->getQuery()
            ->getSingleScalarResult();

        $qb = $this->createQueryBuilder('u');
        $totalAdmins = $qb->select('COUNT(u)')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_ADMIN%')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total' => $totalUsers,
            'active' => $totalActive,
            'admins' => $totalAdmins,
            'merchants' => $totalUsers - $totalAdmins // Regular users (not admins)
        ];
    }

    /**
     * Find recent users for the admin dashboard
     */
    public function findRecent(int $limit = 5): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.dateCreation', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search users by email or role
     */
    public function searchForAdmin(string $term): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.email LIKE :term')
            ->orWhere('u.roles LIKE :roleterm')
            ->setParameter('term', '%' . $term . '%')
            ->setParameter('roleterm', '%' . $term . '%')
            ->orderBy('u.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
