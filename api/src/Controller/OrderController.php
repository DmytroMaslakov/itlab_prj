<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
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
    #[Route('orders', name: 'create_order', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['user'],
            $requestData['products'],
            $requestData['price'],
            $requestData['description'])) {
            throw new BadRequestException();
        }

        $order = new Order();

        $user = $this->entityManager->getRepository(User::class)->find($requestData['user']);

        if (!$user) {
            throw new BadRequestException();
        }

        $products = new ArrayCollection();
        foreach ($requestData['products'] as $product_id){
            $product = $this->entityManager->getRepository(Product::class)->find($product_id);
            $order->addProduct($product);
        }

        $order
            ->setUser($user)
            ->setPrice($requestData['price'])
            ->setDescription($requestData['description']);

        $this->entityManager->persist($order);

        $this->entityManager->flush();

        return new JsonResponse($order, Response::HTTP_CREATED);
    }

    #[Route('orders', name: 'read_orders', methods: ["GET"])]
    public function read(): JsonResponse
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();

        return new JsonResponse($orders, Response::HTTP_OK);
    }

    #[Route('orders/{id}', name: 'read_order_by_id', methods: ['GET'])]
    public function readById(string $id): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($order, Response::HTTP_OK);
    }

    #[Route('orders/{id}', name: 'update_orders', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            throw new NotFoundHttpException();
        }

        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['price'],
            $requestData['description']
        )) {
            throw new BadRequestException();
        }

        $order
            ->setPrice($requestData['price'])
            ->setDescription($requestData['description']);

        $this->entityManager->flush();

        return new JsonResponse($order, Response::HTTP_OK);
    }

    #[Route('orders/{id}', name: 'delete_order', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
