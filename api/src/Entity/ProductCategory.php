<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints\ProductCategory as ProductCategoryConstraint;

#[ORM\Entity(repositoryClass: ProductCategoryRepository::class)]
#[ProductCategoryConstraint]
#[ApiResource(
    collectionOperations: [
        "get"  => [
            "method" => "GET",
        ],
        "post" => [
            "method" => "POST",
            "security" => "is_granted('".User::ROLE_ADMIN."')"
        ]
    ],
    itemOperations: [
        "get"    => [
            "method" => "GET"
        ],
        "put"    => [
            "method" => "PUT",
            "security" => "is_granted('".User::ROLE_ADMIN."')"
        ],
        "delete" => [
            "method" => "DELETE",
            "security" => "is_granted('".User::ROLE_ADMIN."')"
        ]
    ]
)]
class ProductCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
