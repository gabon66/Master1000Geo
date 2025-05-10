<?php

namespace App\Tournament\Application\Query;

class GetAllTournamentsQuery
{
    private ?string $gender;

    public function __construct(?string $gender)
    {
        $this->gender = $gender;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }
}