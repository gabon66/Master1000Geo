<?php

namespace App\Player\Domain\Repository;

use App\Player\Domain\Entity\Player;

interface PlayerRepositoryInterface
{
    public function find(int $id): ?Player;
    public function findAll(): array;
    public function save(Player $player): void;
    public function remove(Player $player): void;
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;
    public function findOneBy(array $criteria): ?Player;
    // Podríamos añadir métodos específicos para Player, como buscar por ranking
    // public function findPlayersOrderedByPoints(string $order = 'DESC'): array;
}