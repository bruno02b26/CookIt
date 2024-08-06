<?php

namespace App\Repository;

use App\Entity\DTO\RecipeSummary;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function findRecipeSummaries(): array
    {
        $results = $this->createQueryBuilder('r')
            ->select('r.id, r.title, r.image, r.addTime')
            ->getQuery()
            ->getArrayResult();

        return array_map(function ($row) {
            $date = $row['addTime']->format('Y-m-d');
            $summary = new RecipeSummary($row['id'], $row['title'], $row['image'], $date);
            return $summary->toArray();
        }, $results);
    }

    public function findUserEmailByRecipeId(int $id): ?string
    {
        $result = $this->createQueryBuilder('r')
            ->select('u.email')
            ->innerJoin('r.user', 'u')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? $result : null;
    }

    public function findRecipeDetails(int $id): ?Recipe
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.recipeType', 'rt')
            ->leftJoin('r.cuisine', 'c')
            ->addSelect('rt', 'c')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        $recipe = $qb->getOneOrNullResult();

        if (!$recipe) {
            return null;
        }

        return $recipe;
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
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

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
