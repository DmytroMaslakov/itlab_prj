<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * RoomController constructor
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('category', name: 'create_category', methods: ["POST"])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['name'],
            $requestData['minPrice'],
            $requestData['minPersons'],
            $requestData['maxPrice'],
            $requestData['maxPersons'])) {
            throw new Exception('Invalid request data');
        }

        $category = new Category();

        $category
            ->setName($requestData['name'])
            ->setMinPrice($requestData['minPrice'])
            ->setMinPersons($requestData['minPersons'])
            ->setMaxPrice($requestData['maxPrice'])
            ->setMaxPersons($requestData['maxPersons']);

        $this->entityManager->persist($category);

        $this->entityManager->flush();

        return new JsonResponse($category, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('category', name: 'get_all_categories', methods: ["GET"])]
    public function getAll(): JsonResponse
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();

        return new JsonResponse($categories, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('category/{id}', name: 'get_category_by_id', methods: ["GET"])]
    public function getById(string $id): JsonResponse
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        return new JsonResponse($category, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('category/{id}', name: 'update_category', methods: ["PUT"])]
    public function update(string $id, Request $request): JsonResponse
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw new Exception('Category with id ' . $id . ' not found');
        }

        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['name'],
            $requestData['minPrice'],
            $requestData['minPersons'],
            $requestData['maxPrice'],
            $requestData['maxPersons'])) {
            throw new Exception('Invalid request data');
        }

        $category
            ->setName($requestData['name'])
            ->setMinPrice($requestData['minPrice'])
            ->setMinPersons($requestData['minPersons'])
            ->setMaxPrice($requestData['maxPrice'])
            ->setMaxPersons($requestData['maxPersons']);

        $this->entityManager->flush();

        return new JsonResponse($category, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('category/{id}', name: 'delete_category', methods: ["DELETE"])]
    public function delete(string $id): JsonResponse
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw new Exception('Category with id ' . $id . ' not found');
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return new JsonResponse(Response::HTTP_NO_CONTENT);
    }
}
