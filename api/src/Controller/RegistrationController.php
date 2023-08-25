<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {

        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('/registration', name: 'app_registration', methods: ["POST"])]
    public function index(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if(!isset(
            $requestData['email'],
            $requestData['password']
        )){
            throw new Exception('Invalid data');
        }

        $user = new User();

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $requestData['password']
        );

        $user
            ->setEmail($requestData['email'])
            ->setPassword($hashedPassword)
            ->setRoles(["ROLE_USER"]);

        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return new JsonResponse($user, Response::HTTP_CREATED);
    }
}
