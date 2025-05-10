<?php

namespace App\Tournament\Domain\Repository;

use App\Tournament\Domain\Entity\Tournament;

interface TournamentRepositoryInterface
{
    /**
     * @return Tournament[]
     */
    public function findAll(): array;

    public function find(int $id): ?Tournament;

    public function findOneBy(array $criteria, ?array $orderBy = null): ?Tournament;

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    public function add(Tournament $entity, bool $flush = false): void;

    public function remove(Tournament $entity, bool $flush = false): void;
}