<?php

namespace App\Entity;

use App\Repository\FloorRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FloorRepository::class)]
class Floor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'floor', targetEntity: Room::class)]
    private Collection $rooms;

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
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

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

}

