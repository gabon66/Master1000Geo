<?php

namespace App\Player\Application\Query;

use App\Player\Domain\ValueObject\Gender;

class ListPlayersQuery
{
    private ?Gender $gender;
    private ?string $orderBy;
    private ?string $orderDirection = 'ASC';
    private ?int $limit;

    public function __construct(?Gender $gender = null, ?string $orderBy = null, ?string $orderDirection = 'ASC', ?int $limit = null)
    {
        $this->gender = $gender;
        $this->orderBy = $orderBy;
        $this->orderDirection = strtoupper($orderDirection);
        $this->limit = $limit;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function getOrderDirection(): ?string
    {
        return $this->orderDirection;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }
}