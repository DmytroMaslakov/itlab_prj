<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * OrderController constructor
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
    #[Route('order', name: 'create_order', methods: ["POST"])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['room'],
            $requestData['user'],
            $requestData['price'])) {
            throw new Exception('Invalid request data');
        }

        $order = new Order();

        $room = $this->entityManager->getRepository(Room::class)->find($requestData['room']);
        $user = $this->entityManager->getRepository(User::class)->find($requestData['user']);

        if (!$room) {
            throw new Exception('Room with id ' . $requestData['room'] . ' not found');
        }
        if(!$user){
            throw new Exception('User with id ' . $requestData['user'] . ' not found');
        }

        $order
            ->setRoom($room)
            ->setUser($user)
            ->setPrice($requestData['price']);

        $this->entityManager->persist($order);

        $this->entityManager->flush();

        return new JsonResponse($order, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('order', name: 'get_all_orders', methods: ["GET"])]
    public function getAll(): JsonResponse
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();

        return new JsonResponse($orders, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('order/{id}', name: 'get_order_by_id', methods: ["GET"])]
    public function getById(string $id): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        return new JsonResponse($order, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('order/{id}', name: 'update_order', methods: ["PUT"])]
    public function update(string $id, Request $request): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            throw new Exception('Order with id ' . $id . ' not found');
        }

        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['user'],
            $requestData['room'],
            $requestData['price'])) {
            throw new Exception('Invalid request data');
        }

        $room = $this->entityManager->getRepository(Room::class)->find($requestData['room']);
        $user = $this->entityManager->getRepository(User::class)->find($requestData['user']);

        if (!$room) {
            throw new Exception('Room with id ' . $requestData['room'] . ' not found');
        }
        if(!$user){
            throw new Exception('User with id ' . $requestData['user'] . ' not found');
        }

        $order
            ->setRoom($room)
            ->setUser($user)
            ->setPrice($requestData['price']);

        $this->entityManager->flush();

        return new JsonResponse($order, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('order/{id}', name: 'delete_order', methods: ["DELETE"])]
    public function delete(string $id): JsonResponse
    {
        $room = $this->entityManager->getRepository(Room::class)->find($id);

        if (!$room) {
            throw new Exception('Room with id ' . $id . ' not found');
        }

        $this->entityManager->remove($room);
        $this->entityManager->flush();

        return new JsonResponse(Response::HTTP_NO_CONTENT);
    }
}
