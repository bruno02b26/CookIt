<?php

namespace App\Repository;

use App\Entity\ShoppingListIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShoppingListIngredient>
 *
 * @method ShoppingListIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingListIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingListIngredient[]    findAll()
 * @method ShoppingListIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingListIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingListIngredient::class);
    }

    public function findListIngredients(int $listId): array
    {
        $qb = $this->createQueryBuilder('sli')
            ->select('sli.quantity, f.name AS fraction, u.abbr AS unit, i.name AS ingredient, sli.note')
            ->innerJoin('sli.fraction', 'f')
            ->innerJoin('sli.unit', 'u')
            ->innerJoin('sli.ingredient', 'i')
            ->where('sli.shoppingList = :listId')
            ->setParameter('listId', $listId)
            ->getQuery();

        return $qb->getArrayResult();
    }

    //    /**
    //     * @return ShoppingListIngredient[] Returns an array of ShoppingListIngredient objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ShoppingListIngredient
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
