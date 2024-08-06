<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRecipeUserActionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function show(UserRecipeUserActionRepository $userRecipeUserActionRepository,
                         EntityManagerInterface $entityManager): Response
    {
        //CHANGE_USER
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new Response('User not found', Response::HTTP_UNAUTHORIZED);
        }
        $userId = $user->getId();
//        $user = $entityManager->getRepository(User::class)->find(1);

        $bookmarkedRecipes = $userRecipeUserActionRepository->findByUserAction($user, 'favorite');
        $likedRecipes = $userRecipeUserActionRepository->findByUserAction($user, 'liked');

        return $this->render('user/user.html.twig', [
            'user' => $user,
            'bookmarkedRecipes' => $bookmarkedRecipes,
            'likedRecipes' => $likedRecipes,
        ]);
    }
}