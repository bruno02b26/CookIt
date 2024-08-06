<?php

namespace App\Repository;

use App\Entity\DietaryType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DietaryType>
 *
 * @method DietaryType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DietaryType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DietaryType[]    findAll()
 * @method DietaryType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DietaryTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DietaryType::class);
    }

    public function findAllSortedByName()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return DietaryType[] Returns an array of DietaryType objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DietaryType
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
