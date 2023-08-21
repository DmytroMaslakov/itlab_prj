<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category implements JsonSerializable
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
    private ?string $minPrice = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $minPersons = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: '0')]
    private ?string $maxPrice = null;

    /**
     * @var int|null
     */
    #[ORM\Column]
    private ?int $maxPersons = null;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: "category", targetEntity: Room::class)]
    private Collection $rooms;

    /**
     * Category constructor
     */
    public function __construct()
    {
        $this->rooms = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getMinPrice(): ?string
    {
        return $this->minPrice;
    }

    /**
     * @param string|null $minPrice
     * @return $this
     */
    public function setMinPrice(?string $minPrice): self
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinPersons(): ?int
    {
        return $this->minPersons;
    }

    /**
     * @param int|null $minPersons
     * @return $this
     */
    public function setMinPersons(?int $minPersons): self
    {
        $this->minPersons = $minPersons;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMaxPrice(): ?string
    {
        return $this->maxPrice;
    }

    /**
     * @param string|null $maxPrice
     * @return $this
     */
    public function setMaxPrice(?string $maxPrice): self
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxPersons(): ?int
    {
        return $this->maxPersons;
    }

    /**
     * @param int|null $maxPersons
     * @return $this
     */
    public function setMaxPersons(?int $maxPersons): self
    {
        $this->maxPersons = $maxPersons;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    /**
     * @param Collection $rooms
     * @return $this
     */
    public function setRooms(Collection $rooms): self
    {
        $this->rooms = $rooms;

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
     * @return array
     */
    public function jsonSerialize() : array
    {
        return [
            'id'         => $this->getId(),
            'name'       => $this->getName(),
            'minPrice'   => $this->getMinPrice(),
            'minPersons' => $this->getMinPersons(),
            'maxPrice'   => $this->getMaxPrice(),
            'maxPersons' => $this->getMaxPersons()
        ];
    }
}
