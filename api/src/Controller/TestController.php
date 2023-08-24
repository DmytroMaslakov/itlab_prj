<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
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
    #[Route(path: 'test', name: 'app_test')]
    public function test(): JsonResponse
    {
        $user = $this->getUser();

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        if (in_array(User::ROLE_ADMIN, $user->getRoles())) {
            return new JsonResponse($products);
        }
        return new JsonResponse($this->fetchedProductsForUser($products));
    }

    /**
     * @param array $products
     * @return array
     */
    public function fetchedProductsForUser(array $products): array{
        $fetchedProductsForUser = null;
        /** @var Product $product */
        foreach ($products as $product) {
            $tmpProductData = $product->jsonSerialize();

            unset($tmpProductData['description']);
            $fetchedProductsForUser[] = $tmpProductData;
        }
        return $fetchedProductsForUser;
    }
}
