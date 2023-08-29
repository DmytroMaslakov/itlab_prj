<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity()]
class Test implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $test = null;





    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Test
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTest(): ?string
    {
        return $this->test;
    }

    /**
     * @param string|null $test
     * @return $this
     */
    public function setTest(?string $test): self
    {
        $this->test = $test;

        return $this;
    }



    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'test' => $this->getTest()
        ];
    }
}
