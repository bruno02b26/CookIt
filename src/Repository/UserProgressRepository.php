<?php

namespace App\Repository;

use App\Entity\UserProgress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProgress>
 *
 * @method UserProgress|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProgress|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProgress[]    findAll()
 * @method UserProgress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProgressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProgress::class);
    }

    public function findByUserId(int $userId): array
    {
        $qb = $this->createQueryBuilder('up')
            ->select('up.id, r.id as id, r.title as recipeName, r.image, up.startTime, up.endTime')
            ->innerJoin('up.recipe', 'r')
            ->where('up.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();

        return $qb->getArrayResult();
    }

    public function countAllRecipesByUser(int $userId): int
    {
        return $this->createQueryBuilder('up')
            ->select('COUNT(up.id)')
            ->where('up.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countUniqueRecipesByUser(int $userId): int
    {
        return $this->createQueryBuilder('up')
            ->select('COUNT(DISTINCT up.recipe)')
            ->where('up.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findTop5FavoriteRecipesByUser(int $userId): array
    {
        return $this->createQueryBuilder('up')
            ->select('r.id, r.title, COUNT(up.id) as occurrences')
            ->innerJoin('up.recipe', 'r')
            ->where('up.user = :userId')
            ->setParameter('userId', $userId)
            ->groupBy('r.id')
            ->orderBy('occurrences', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getArrayResult();
    }

    public function findTop5FavoriteRecipeTypesByUser(int $userId): array
    {
        return $this->createQueryBuilder('up')
            ->select('rt.id, rt.name, COUNT(up.id) as occurrences')
            ->innerJoin('up.recipe', 'r')
            ->innerJoin('r.recipeType', 'rt')
            ->where('up.user = :userId')
            ->setParameter('userId', $userId)
            ->groupBy('rt.id')
            ->orderBy('occurrences', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getArrayResult();
    }

    public function findTop5FavoriteCuisinesByUser(int $userId): array
    {
        return $this->createQueryBuilder('up')
            ->select('c.id, c.name, COUNT(up.id) as occurrences')
            ->innerJoin('up.recipe', 'r')
            ->innerJoin('r.cuisine', 'c')
            ->where('up.user = :userId')
            ->setParameter('userId', $userId)
            ->groupBy('c.id')
            ->orderBy('occurrences', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getArrayResult();
    }

    //    /**
    //     * @return UserProgress[] Returns an array of UserProgress objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserProgress
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
