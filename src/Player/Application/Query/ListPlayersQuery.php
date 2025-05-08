<?php

namespace App\Player\Application\Query;

class ListPlayersQuery
{
    private ?bool $gender;

    public function __construct(?bool $gender)
    {
        $this->gender = $gender;
    }

    public function getGender(): ?bool
    {
        return $this->gender;
    }
}