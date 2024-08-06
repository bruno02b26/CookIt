<?php

namespace App\Repository;

use App\Entity\RecipeDietaryType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecipeDietaryType>
 *
 * @method RecipeDietaryType|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeDietaryType|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeDietaryType[]    findAll()
 * @method RecipeDietaryType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeDietaryTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeDietaryType::class);
    }

    public function findRecipeDietaryTypes(int $id) {
        return $this->createQueryBuilder('rdt')
            ->select('dt.name')
            ->innerJoin('rdt.dietaryType', 'dt')
            ->andWhere('rdt.recipe = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();
    }

    //    /**
    //     * @return RecipeDietaryType[] Returns an array of RecipeDietaryType objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RecipeDietaryType
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
