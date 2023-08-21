<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 *
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    /**
     * RoomRepository constructor
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /**
     * @param int $itemsPerPage
     * @param int $page
     * @param string|null $name
     * @param string|null $categoryName
     * @param string|null $price
     * @param string|null $floorNumber
     * @param string|null $isBooked
     * @param string|null $minPrice
     * @param string|null $minPersons
     * @param string|null $maxPrice
     * @param string|null $maxPersons
     * @return float|int|mixed|string
     */
    public function getAllRoomsByParams(int     $itemsPerPage, int $page, ?string $name = null,
                                        ?string $categoryName = null, ?string $price = null,
                                        ?string $floorNumber = null, ?string $isBooked = null,
                                        ?string $minPrice = null, ?string $minPersons = null,
                                        ?string $maxPrice = null, ?string $maxPersons = null): mixed
    {
        return $this->createQueryBuilder("room")
            ->join('room.category', 'category')

            ->andWhere('category.name LIKE :categoryName')
            ->setParameter('categoryName', $categoryName)

            ->andWhere('category.minPrice LIKE :minPrice')
            ->setParameter('minPrice', $minPrice)

            ->andWhere('category.minPersons LIKE :minPersons')
            ->setParameter('minPersons', $minPersons)

            ->andWhere('category.maxPrice LIKE :maxPrice')
            ->setParameter('maxPrice', $maxPrice)

            ->andWhere('category.maxPersons LIKE :maxPersons')
            ->setParameter('maxPersons', $maxPersons)

            ->andWhere('room.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')

            ->andWhere('room.price LIKE :price')
            ->setParameter('price', $price ?? '%')

            ->andWhere('room.floorNumber LIKE :floorNumber')
            ->setParameter('floorNumber', $floorNumber)

            ->andWhere('room.isBooked LIKE :isBooked')
            ->setParameter('isBooked', $isBooked)

            ->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage)

            ->getQuery()
            ->getResult();
    }
}
