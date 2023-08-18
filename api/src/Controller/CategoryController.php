<?php

namespace App\Controller;

use App\Entity\Category;
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
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @return JsonResponse
     */
    #[Route('category-read', name: 'category_read')]
    public function read(): JsonResponse
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();

        return new JsonResponse($categories, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('category-create', name: 'category_create')]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['name'],
            $requestData['price'])) {

            throw new Exception("Invalid request daa");
        }

        $category = new Category();

        $category->setName($requestData['name'])
            ->setPrice($requestData['price']);

        $this->entityManager->persist($category);

        $this->entityManager->flush();

        return new JsonResponse($category, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('category-update/{id}', name: 'category_update')]
    public function update(string $id, Request $request): JsonResponse
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);
        $requestData = json_decode($request->getContent(), true);

        if(!$category){
            throw new Exception("Category with id ". $id . " not found");
        }
        $fl = false;
        if(isset($requestData['name'])){
            $category->setName($requestData['name']);
            $fl = true;
        }
        if(isset($requestData['price'])){
            $category->setPrice($requestData['price']);
            $fl = true;
        }
        if($fl)
            $this->entityManager->flush();
        else
            throw new Exception('invalid data');

        return new JsonResponse($category, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('category-delete/{id}', name: 'category_delete')]
    public function delete(string $id): JsonResponse
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if(!$category){
            throw new Exception("Category with id ". $id . " not found");
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();
        return new JsonResponse($category, Response::HTTP_NO_CONTENT);
    }
}
