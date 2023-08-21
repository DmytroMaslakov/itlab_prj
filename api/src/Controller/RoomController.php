<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Room;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
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
    #[Route('room', name: 'create_room', methods: ["POST"])]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['name'],
            $requestData['category'],
            $requestData['price'],
            $requestData['floorNumber'],
            $requestData['isBooked'])) {
            throw new Exception('Invalid request data');
        }

        $room = new Room();

        $category = $this->entityManager->getRepository(Category::class)->find($requestData['category']);

        if (!$category) {
            throw new Exception('Category with id ' . $requestData['category'] . ' not found');
        }

        $room
            ->setName($requestData['name'])
            ->setCategory($requestData['category'])
            ->setPrice($requestData['price'])
            ->setFloorNumber($requestData['floorNumber'])
            ->setIsBooked($requestData['isBooked']);

        $this->entityManager->persist($room);

        $this->entityManager->flush();

        return new JsonResponse($room, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[Route('room', name: 'get_all_rooms', methods: ["GET"])]
    public function getAll(): JsonResponse
    {
        $rooms = $this->entityManager->getRepository(Room::class)->findAll();

        return new JsonResponse($rooms, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('room/{id}', name: 'get_room_by_id', methods: ["GET"])]
    public function getById(string $id): JsonResponse
    {
        $room = $this->entityManager->getRepository(Room::class)->find($id);

        return new JsonResponse($room, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('room/{id}', name: 'update_room', methods: ["PUT"])]
    public function update(string $id, Request $request): JsonResponse
    {
        $room = $this->entityManager->getRepository(Room::class)->find($id);

        if (!$room) {
            throw new Exception('Room with id ' . $id . ' not found');
        }

        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['name'],
            $requestData['category'],
            $requestData['price'],
            $requestData['floorNumber'],
            $requestData['isBooked'])) {
            throw new Exception('Invalid request data');
        }

        $category = $this->entityManager->getRepository(Category::class)->find($requestData['category']);

        if (!$category) {
            throw new Exception('Category with id ' . $requestData['category'] . ' not found');
        }

        $room
            ->setName($requestData['name'])
            ->setCategory($requestData['category'])
            ->setPrice($requestData['price'])
            ->setFloorNumber($requestData['floorNumber'])
            ->setIsBooked($requestData['isBooked']);

        $this->entityManager->flush();

        return new JsonResponse($room, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('room/{id}', name: 'delete_room', methods: ["DELETE"])]
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
