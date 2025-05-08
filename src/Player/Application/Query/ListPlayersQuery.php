<?php

namespace App\Player\Application\Query;

use App\Player\Domain\ValueObject\Gender;

class ListPlayersQuery
{
    private ?Gender $gender;

    public function __construct(?Gender $gender)
    {
        $this->gender = $gender;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }
}