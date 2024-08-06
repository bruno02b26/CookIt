<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProgress;
use App\Repository\RecipeRepository;
use App\Repository\UserProgressRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProgressController extends AbstractController
{
    #[Route('/cooking', name: 'app_cooking')]
    public function cooking(UserProgressRepository $userProgressRepository): Response
    {
//        $userId = 1;

        //CHANGE_USER
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new Response('User not found', Response::HTTP_UNAUTHORIZED);
        }
        $userId = $user->getId();

        $progresses = $userProgressRepository->findByUserId($userId);

        return $this->render('progress/cooking.html.twig', [
            'progresses' => $progresses,
        ]);
    }

    #[Route('/stats', name: 'app_stats')]
    public function stats(UserProgressRepository $userProgressRepository): Response
    {
//        $userId = 1;

        //CHANGE_USER
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new Response('User not found', Response::HTTP_UNAUTHORIZED);
        }
        $userId = $user->getId();

        $totalRecipes = $userProgressRepository->countAllRecipesByUser($userId);
        $uniqueRecipes = $userProgressRepository->countUniqueRecipesByUser($userId);
        $favoriteRecipes = $userProgressRepository->findTop5FavoriteRecipesByUser($userId);
        $favoriteRecipeTypes = $userProgressRepository->findTop5FavoriteRecipeTypesByUser($userId);
        $favoriteCuisines = $userProgressRepository->findTop5FavoriteCuisinesByUser($userId);

        return $this->render('progress/stats.html.twig', [
            'totalRecipes' => $totalRecipes,
            'uniqueRecipes' => $uniqueRecipes,
            'favoriteRecipes' => $favoriteRecipes,
            'favoriteRecipeTypes' => $favoriteRecipeTypes,
            'favoriteCuisines' => $favoriteCuisines,
        ]);
    }

    #[Route('/cooking/add_cooking', name: 'app_log_add', methods: ['POST'])]
    public function addLog(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository,
                           RecipeRepository $recipeRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid JSON: ' . json_last_error_msg()], 400);
        }

//        $userId = $data['id_user'] ?? null;
//        $user = $entityManager->getRepository(User::class)->find(1);
//        $userId = $user->getId();

        //CHANGE_USER
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new Response('User not found', Response::HTTP_UNAUTHORIZED);
        }
        $userId = $user->getId();

        $name = $data['name'] ?? null;
        $recipeId = $data['recipe_id'] ?? null;
        $startDate = $data['start_date'] ?? null;
        $startTime = $data['start_time'] ?? null;
        $endDate = $data['end_date'] ?? null;
        $endTime = $data['end_time'] ?? null;

        if (!$userId || !$name || !$recipeId || !$startDate || !$startTime || !$endDate || !$endTime) {
            return new JsonResponse(['status' => 'error', 'message' => 'Missing required fields'], 400);
        }

        $user = $userRepository->find($userId);
        $recipe = $recipeRepository->find($recipeId);

        if (!$user || !$recipe) {
            return new JsonResponse(['status' => 'error', 'message' => 'User or Recipe not found'], 404);
        }

        try {
            $startDateTime = new \DateTime("$startDate $startTime");
            $endDateTime = new \DateTime("$endDate $endTime");
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid date/time format'], 400);
        }

        $userProgress = new UserProgress($user, $recipe, $startDateTime, $endDateTime);

        $entityManager->persist($userProgress);
        $entityManager->flush();

        return new JsonResponse(['status' => 'success', 'id' => $userProgress->getId()], 201);
    }
}
