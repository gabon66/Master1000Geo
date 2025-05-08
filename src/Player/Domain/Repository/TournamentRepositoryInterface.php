<?php

namespace App\Player\Domain\Repository;

use App\Tournament\Domain\Entity\Tournament;

interface TournamentRepositoryInterface
{
    public function find(int $id): ?Tournament;
    public function findAll(): array;
    public function save(Tournament $tournament): void;
    public function remove(Tournament $tournament): void;
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;
    public function findOneBy(array $criteria): ?Tournament;
    // Podríamos añadir métodos específicos para Tournament si los necesitamos
}