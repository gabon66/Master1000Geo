<?php

namespace App\Player\Infrastructure\Repository;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Service\Attribute\AsService;

#[AsService]
class PlayerRepository implements PlayerRepositoryInterface
{
    private EntityRepository $objectRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $entityManager->getRepository(Player::class);
    }

    public function find(int $id): ?Player
    {
        return $this->objectRepository->find($id);
    }

    public function findAll(): array
    {
        return $this->objectRepository->findAll();
    }

    public function save(Player $player): void
    {
        $this->entityManager->persist($player);
        $this->entityManager->flush();
    }

    public function remove(Player $player): void
    {
        $this->entityManager->remove($player);
        $this->entityManager->flush();
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->objectRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria): ?Player
    {
        return $this->objectRepository->findOneBy($criteria);
    }

    // Ejemplo de implementación del método comentado en la interfaz
    // public function findPlayersOrderedByPoints(string $order = 'DESC'): array
    // {
    //     return $this->objectRepository->findBy([], ['points' => $order]);
    // }
}