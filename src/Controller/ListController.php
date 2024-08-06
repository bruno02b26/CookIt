<?php

namespace App\Controller;

use App\Entity\ShoppingList;
use App\Entity\ShoppingListIngredient;
use App\Entity\User;
use App\Repository\FractionRepository;
use App\Repository\IngredientRepository;
use App\Repository\ShoppingListIngredientRepository;
use App\Repository\ShoppingListRepository;
use App\Repository\UnitRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListController extends AbstractController
{
    #[Route('/lists', name: 'app_lists')]
    public function lists(ShoppingListRepository $sl, ShoppingListIngredientRepository $sli): Response
    {
        //CHANGE_USER
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new Response('User not found', Response::HTTP_UNAUTHORIZED);
        }
        $userId = $user->getId();
//        $userId = 1;

        $lists = $sl->findListsbyUserId($userId);

        $listsWithIngredients = [];
        foreach ($lists as $list) {
            $ingredients = $sli->findListIngredients($list['id']);
            $ingredientCount = count($ingredients);
            $listsWithIngredients[] = [
                'list' => $list,
                'ingredients' => $ingredients,
                'ingredientCount' => $ingredientCount
            ];
        }

        return $this->render('lists/lists.html.twig', [
            'lists' => $listsWithIngredients,
        ]);
    }

    #[Route('/lists/add_list', name: 'app_list_add', methods: ['POST'])]
    public function addList(Request        $request, EntityManagerInterface $entityManager,
                            UserRepository $userRepository, FractionRepository $fractionRepository,
                            UnitRepository $unitRepository, IngredientRepository $ingredientRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $title = $data['title'] ?? null;
        $ingredientsData = json_decode($data['ingredients'], true);

//        $userId = $data['id_user'] ?? null;

        //CHANGE_USER
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new Response('User not found', Response::HTTP_UNAUTHORIZED);
        }
        $userId = $user->getId();
//        $user = $entityManager->getRepository(User::class)->find(1);
//        $userId = $user->getId();

        $shoppingList = new ShoppingList();
        $shoppingList->setTitle($title);
        $shoppingList->setUser($user);
        $shoppingList->setAddTime(new \DateTime());

        $entityManager->persist($shoppingList);
        $entityManager->flush();

        foreach ($ingredientsData as $ingredientData) {
            $quantity = $ingredientData['quantity'];
            $fraction = $fractionRepository->findByName($ingredientData['fraction']);
            $unit = $unitRepository->findByNameOrAbbr($ingredientData['unit']);
            $ingredient = $ingredientRepository->findByName($ingredientData['ingredient']);
            $note = $ingredientData['note'];

            $shoppingListIngredient = new ShoppingListIngredient(
                $shoppingList,
                $quantity,
                $fraction,
                $unit,
                $ingredient,
                $note
            );

            $entityManager->persist($shoppingListIngredient);
        }

        $entityManager->flush();

        sleep(2);

        return new JsonResponse(['status' => 'success'], 200);
    }

    #[Route('/lists/delete/{id}', name: 'app_list_delete', methods: ['DELETE'])]
    public function deleteList(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $shoppingList = $entityManager->getRepository(ShoppingList::class)->find($id);

        if (!$shoppingList) {
            return new JsonResponse(['status' => 'error', 'message' => 'List not found'], 404);
        }

        $ingredients = $entityManager->getRepository(ShoppingListIngredient::class)->findBy(['shoppingList' => $id]);

        foreach ($ingredients as $ingredient) {
            $entityManager->remove($ingredient);
        }

        $entityManager->remove($shoppingList);
        $entityManager->flush();

        return new JsonResponse(['status' => 'success'], 200);
    }
}
