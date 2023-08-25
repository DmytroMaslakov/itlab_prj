<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class OrderController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var Security
     */
    private Security $security;

    /**
     * OrderController constructor
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('order', name: 'create_order', methods: ["POST"])]
    #[IsGranted("ROLE_USER")]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['room'],
            $requestData['price'])) {
            throw new Exception('Invalid request data');
        }

        $order = new Order();

        $room = $this->entityManager->getRepository(Room::class)->find($requestData['room']);
        $user = $this->entityManager->getRepository(User::class)->findByEmail($this->security->getUser()->getUserIdentifier())[0];

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
    #[IsGranted("ROLE_USER")]
    public function getAll(): JsonResponse
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        $userOrders = $this->fetchedOrdersForUser($orders);
        return new JsonResponse($userOrders, Response::HTTP_OK);
    }

    /**
     * @param array $orders
     * @return array
     */
    public function fetchedOrdersForUser(array $orders):array{
        $userOrders = [];
        foreach ($orders as $order) {
            if($order->getUser()->getEmail() === $this->security->getUser()->getUserIdentifier()){
                $userOrders []= $order;
            }
        }
        return $userOrders;
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('order/{id}', name: 'get_order_by_id', methods: ["GET"])]
    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_USER')]
    public function update(string $id, Request $request): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order || $order->getUser()->getEmail() !== $this->security->getUser()->getUserIdentifier()) {
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
    #[IsGranted('ROLE_USER')]
    public function delete(string $id): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order || $order->getUser()->getEmail() !== $this->security->getUser()->getUserIdentifier()) {
            throw new Exception('Order with id ' . $id . ' not found');
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
