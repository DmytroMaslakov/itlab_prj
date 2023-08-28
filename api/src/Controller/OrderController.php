<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var DenormalizerInterface
     */
    private DenormalizerInterface $denormalizer;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param DenormalizerInterface $denormalizer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        DenormalizerInterface  $denormalizer,
        ValidatorInterface     $validator)
    {
        $this->entityManager = $entityManager;
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    #[Route('orders', name: 'create_order', methods: ['POST'])]
    #[IsGranted("ROLE_USER")]
    public function create(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $products = $requestData['products'];
        unset($requestData['products']);

        $order = $this->denormalizer->denormalize($requestData, Order::class,"array");

        $user = $this->getUser();

        if (!$user) {
            throw new BadRequestException();
        }

        foreach ($products as $product_id) {
            $product = $this->entityManager->getRepository(Product::class)->find($product_id);
            $order->addProduct($product);
        }

        $order
            ->setUser($user)
            ->setPrice($requestData['price'])
            ->setDescription($requestData['description']);

        $errors = $this->validator->validate($order);

        if(count($errors)>0){
            return new JsonResponse((string)$errors);
        }
        $this->entityManager->persist($order);

        $this->entityManager->flush();

        return new JsonResponse($order, Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    #[IsGranted("ROLE_USER")]
    #[Route('orders', name: 'read_orders', methods: ["GET"])]
    public function read(): JsonResponse
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        $userOrders = $this->fetchedOrdersForUser($orders);

        return new JsonResponse($userOrders, Response::HTTP_OK);
    }

    /**
     * @param array $orders
     * @return array
     */
    public function fetchedOrdersForUser(array $orders): array
    {
        $userOrders = [];
        foreach ($orders as $order) {
            if ($order->getUser()->getEmail() === $this->getUser()->getUserIdentifier()) {
                $userOrders [] = $order;
            }
        }
        return $userOrders;
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('orders/{id}', name: 'read_order_by_id', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function readById(string $id): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order || $order->getUser()->getEmail() !== $this->getUser()->getUserIdentifier()) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($order, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('orders/{id}', name: 'update_orders', methods: ['PUT'])]
    #[IsGranted("ROLE_USER")]
    public function update(string $id, Request $request): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order || $order->getUser()->getEmail() !== $this->getUser()->getUserIdentifier()) {
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

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('orders/{id}', name: 'delete_order', methods: ['DELETE'])]
    #[IsGranted("ROLE_USER")]
    public function delete(string $id): JsonResponse
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order || $order->getUser()->getEmail() !== $this->getUser()->getUserIdentifier()) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
