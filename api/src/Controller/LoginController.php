<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
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
     * @throws Exception
     */
    #[Route('products', name: 'create_product', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['name'],
            $requestData['price'],
            $requestData['description'])){
            throw new BadRequestException();
        }

        $product = new Product();

        $product
            ->setName($requestData['name'])
            ->setPrice($requestData['price'])
            ->setDescription($requestData['description']);

        $this->entityManager->persist($product);

        $this->entityManager->flush();

        return new JsonResponse($product, Response::HTTP_CREATED);
    }

    #[Route('products', name: 'read_product', methods: ["GET"])]
    public function read(): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return new JsonResponse($products, Response::HTTP_OK);
    }

    #[Route('products/{id}', name: 'read_product_by_id', methods: ['GET'])]
    public function readById(string $id): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if(!$product){
            throw new NotFoundHttpException();
        }

        return new JsonResponse($product, Response::HTTP_OK);
    }

    #[Route('products/{id}', name: 'update_product', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if(!$product){
            throw new NotFoundHttpException();
        }

        $requestData = json_decode($request->getContent(), true);

        if(!isset(
            $requestData['name'],
            $requestData['price'],
            $requestData['description']
        )){
            throw new BadRequestException();
        }

        $product
            ->setName($requestData['name'])
            ->setPrice($requestData['price'])
            ->setDescription($requestData['description']);

        $this->entityManager->flush();

        return new JsonResponse($product, Response::HTTP_OK);
    }

    #[Route('products/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if(!$product){
            throw new NotFoundHttpException();
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return new JsonResponse(Response::HTTP_NO_CONTENT);
    }
}
