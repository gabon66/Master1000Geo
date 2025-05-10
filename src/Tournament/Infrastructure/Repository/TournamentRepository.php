<?php

namespace App\Tournament\Infrastructure\Repository;

use App\Tournament\Domain\Entity\Tournament;
use App\Tournament\Domain\Repository\TournamentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Contracts\Service\Attribute\AsService;
#[AsService]
class TournamentRepository implements TournamentRepositoryInterface
{
    private EntityRepository $objectRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $entityManager->getRepository(Tournament::class);
    }

    public function find(int $id): ?Tournament
    {
        return $this->objectRepository->find($id);
    }

    public function findAll(): array
    {
        return $this->objectRepository->findAll();
    }

    public function add(Tournament $tournament, bool $flush = false): void
    {
        $this->entityManager->persist($tournament);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function remove(Tournament $tournament, bool $flush = false): void
    {
        $this->entityManager->remove($tournament);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->objectRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria, ?array $orderBy = null): ?Tournament
    {
        return $this->objectRepository->findOneBy($criteria, $orderBy);
    }

}