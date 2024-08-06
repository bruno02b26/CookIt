<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CuisineRepository;
use App\Repository\DietaryTypeRepository;
use App\Repository\FractionRepository;
use App\Repository\IngredientRepository;
use App\Repository\RecipeTypeRepository;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InfoController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function contact(UnitRepository $unitRepository, CuisineRepository $cuisineRepository,
                            RecipeTypeRepository $recipeTypeRepository, DietaryTypeRepository $dietaryTypeRepository,
                            FractionRepository $fractionRepository, IngredientRepository $ingredientRepository): Response
    {
        $units = $unitRepository->findAllSortedByName();
        $cuisines = $cuisineRepository->findAllSortedByName();
        $recipeTypes = $recipeTypeRepository->findAllSortedByName();
        $dietaryTypes = $dietaryTypeRepository->findAllSortedByName();
        $fractions = $fractionRepository->findAllSortedByName();
        $ingredients = $ingredientRepository->findAllSortedByName();

        return $this->render('info/about.html.twig', [
            'units' => $units,
            'cuisines' => $cuisines,
            'recipeTypes' => $recipeTypes,
            'dietaryTypes' => $dietaryTypes,
            'fractions' => $fractions,
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function about(): Response
    {
        return $this->render('info/contact.html.twig');
    }

    #[Route('/add_entry', name: 'add_entry', methods: ['POST'])]
    public function addEntry(Request $request, EntityManagerInterface $entityManager): Response
    {
        //CHANGE_USER
        $user = $this->getUser();
//        $user = $entityManager->getRepository(User::class)->find(1);

        $data = json_decode($request->getContent(), true);

        $category = $data['category'];
        $name = trim($data['name']);

        if ($category === 'cuisines') {
            $name = ucfirst($name);
        }

        $entityClass = $this->getEntityClass($category);
        if (!$entityClass) {
            return new Response('Invalid category', Response::HTTP_BAD_REQUEST);
        }

        $repository = $entityManager->getRepository($entityClass);
        $repository->findOneBy(['name' => $name]);

        if ($user->getUserType()->getId() === 1) {
            $entry = new $entityClass($name);

            $entityManager->persist($entry);
            $entityManager->flush();

            return new Response("The {$category} with the name \"{$name}\" has been added.", Response::HTTP_CREATED);
        }
        elseif ($user->getUserType()->getId() === 2) {
            return new Response("The {$category} with the name \"{$name}\" has been sent.", Response::HTTP_OK);
        }

        return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    private function getEntityClass(string $category): ?string
    {
        $map = [
            'ingredients' => 'App\Entity\Ingredient',
            'cuisines' => 'App\Entity\Cuisine',
            'recipe_types' => 'App\Entity\RecipeType',
            'dietary_types' => 'App\Entity\DietaryType',
        ];

        return $map[$category] ?? null;
    }
}
