<?php

namespace App\Repository;

use App\Entity\Gemstone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gemstone>
 */
class GemstoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gemstone::class);
    }

    public function search(?string $query, ?string $type, ?string $color, ?string $rarity, ?string $origin): QueryBuilder
    {
        $qb = $this->createQueryBuilder('g');

        if ($query) {
            $qb->andWhere('g.name LIKE :query OR g.description LIKE :query')
               ->setParameter('query', '%' . $query . '%');
        }

        if ($type) {
            $qb->andWhere('g.type = :type')->setParameter('type', $type);
        }

        if ($color) {
            $qb->andWhere('g.color = :color')->setParameter('color', $color);
        }

        if ($rarity) {
            $qb->andWhere('g.rarity = :rarity')->setParameter('rarity', $rarity);
        }

        if ($origin) {
            $qb->andWhere('g.origin LIKE :origin')->setParameter('origin', '%' . $origin . '%');
        }

        return $qb->orderBy('g.name', 'ASC');
    }

    public function findDistinctTypes(): array
    {
        return $this->createQueryBuilder('g')
            ->select('DISTINCT g.type')
            ->where('g.type IS NOT NULL')
            ->orderBy('g.type', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function findDistinctColors(): array
    {
        return $this->createQueryBuilder('g')
            ->select('DISTINCT g.color')
            ->where('g.color IS NOT NULL')
            ->orderBy('g.color', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function findDistinctRarities(): array
    {
        return $this->createQueryBuilder('g')
            ->select('DISTINCT g.rarity')
            ->where('g.rarity IS NOT NULL')
            ->orderBy('g.rarity', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }
}
