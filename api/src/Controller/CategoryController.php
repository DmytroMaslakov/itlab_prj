<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
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
     * @param EntityManagerInterface $enticategory-createtyManager
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
    #[Route('category-create', name: 'category_create')]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['name'], $requestData['type'])) {
            throw new Exception("Invalid request data");
        }

        $category = new Category();

        $category->setName($requestData['name']);
        $category->setType($requestData['type']);

        $this->entityManager->persist($category);

        $this->entityManager->flush();

        return new JsonResponse($category, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('category-read', name: 'product_read')]
    public function read(): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return new JsonResponse($products);
    }

    #[Route('product-all', name: 'product_get_all')]
    public function getAll(): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return new JsonResponse($products);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('product/{id}', name: 'product_get_item')]
    public function getItem(string $id): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception('Product with id ' . $id . ' not found');
        }

        return new JsonResponse($product);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('product-update/{id}', name: 'product_update_item')]
    public function updateProduct(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception('Product with id ' . $id . ' not found');
        }
        $product->setName("new name");

        $this->entityManager->flush();
        return new JsonResponse($product);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('product-delete/{id}', name: 'product_delete_item')]
    public function deleteProduct(string $id): JsonResponse
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new Exception('Product with id ' . $id . ' not found');
        }
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        return new JsonResponse();
    }
}
