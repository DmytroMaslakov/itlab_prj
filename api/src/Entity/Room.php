<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use mysql_xdevapi\Exception;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room implements JsonSerializable
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: '0')]
    private ?string $price = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $floorNumber = null;

    /**
     * @var bool|null
     */
    #[ORM\Column]
    private ?bool $isBooked = null;

    /**
     * @var Category|null
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "rooms")]
    private ?Category $category = null;

    /**
     * @return int|null
     */
    public function getFloorNumber(): ?int
    {
        return $this->floorNumber;
    }

    /**
     * @param int|null $floorNumber
     * @return $this
     */
    public function setFloorNumber(?int $floorNumber): self
    {
        $this->floorNumber = $floorNumber;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsBooked(): ?bool
    {
        return $this->isBooked;
    }

    /**
     * @param bool|null $isBooked
     * @return $this
     */
    public function setIsBooked(?bool $isBooked): self
    {
        $this->isBooked = $isBooked;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): self
    {
        if($price>=$this->category->getMinPrice() && $price<=$this->category->getMaxPrice()){
            $this->price = $price;
        }else{
            throw new Exception('Price must be between max and min price due to category');
        }

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'          => $this->getId(),
            'name'        =>$this->getName(),
            'category'    => $this->getCategory(),
            'price'       => $this->getPrice(),
            'floorNumber' => $this->getFloorNumber(),
            'isBooked'    => $this->getIsBooked()
        ];
    }
}
