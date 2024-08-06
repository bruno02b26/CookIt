<?php

namespace App\Repository;

use App\Entity\DTO\RecipeSummary;
use App\Entity\User;
use App\Entity\UserRecipeUserAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserRecipeUserAction>
 *
 * @method UserRecipeUserAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRecipeUserAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRecipeUserAction[]    findAll()
 * @method UserRecipeUserAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRecipeUserActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRecipeUserAction::class);
    }

    public function findByUserAction(User $user, string $action)
    {
        $results = $this->createQueryBuilder('ura')
            ->innerJoin('ura.recipe', 'r')
            ->innerJoin('ura.userAction', 'ua')
            ->where('ura.user = :user')
            ->andWhere('ua.userAction = :action')
            ->setParameter('user', $user)
            ->setParameter('action', $action)
            ->select('r.id, r.title, r.image, r.addTime')
            ->orderBy('r.title', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(function ($result) {
            return new RecipeSummary(
                $result['id'],
                $result['title'],
                $result['image'],
                $result['addTime']->format('Y-m-d H:i:s')
            );
        }, $results);
    }

    //    /**
    //     * @return UserRecipeUserAction[] Returns an array of UserRecipeUserAction objects
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

    //    public function findOneBySomeField($value): ?UserRecipeUserAction
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
