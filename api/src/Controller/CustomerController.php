<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
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
    #[Route('customer-read', name: 'customer_read')]
    public function read(): JsonResponse
    {
        $customers = $this->entityManager->getRepository(Customer::class)->findAll();

        return new JsonResponse($customers, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('customer-create', name: 'customer_create')]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset(
            $requestData['name'],
            $requestData['surname'],
            $requestData['room'])) {

            throw new Exception("Invalid request daa");
        }

        $customer = new Customer();
        $room = $this->entityManager->getRepository(Room::class)->find($requestData['room']);
        $customer->setName($requestData['name'])
            ->setSurname($requestData['surname'])
            ->setRoom($room);

        $this->entityManager->persist($customer);

        $this->entityManager->flush();

        return new JsonResponse($customer, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('customer-update/{id}', name: 'customer_update')]
    public function update(string $id, Request $request): JsonResponse
    {
        $customer = $this->entityManager->getRepository(Customer::class)->find($id);
        $requestData = json_decode($request->getContent(), true);
        $room = $this->entityManager->getRepository(Room::class)->find($requestData['room']);

        if (!$customer) {
            throw new Exception("Customer with id " . $id . " not found");
        }
        $fl = false;
        if (isset($requestData['name'])) {
            $customer->setName($requestData['name']);
            $fl = true;
        }
        if (isset($requestData['surname'])) {
            $customer->setPrice($requestData['surname']);
            $fl = true;
        }
        if(isset($room)){
            $customer->setRoom($room);
            $fl=true;
        }
        if ($fl)
            $this->entityManager->flush();
        else
            throw new Exception('invalid data');

        return new JsonResponse($customer, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('customer-delete/{id}', name: 'customer_delete')]
    public function delete(string $id): JsonResponse
    {
        $customer = $this->entityManager->getRepository(Customer::class)->find($id);

        if (!$customer) {
            throw new Exception("Customer with id " . $id . " not found");
        }

        $this->entityManager->remove($customer);
        $this->entityManager->flush();
        return new JsonResponse($customer, Response::HTTP_NO_CONTENT);
    }
}
