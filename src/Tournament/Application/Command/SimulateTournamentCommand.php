<?php

namespace App\Tournament\Application\Command;

final class SimulateTournamentCommand
{
    private string $gender;

    public function __construct(string $gender)
    {
        if (!in_array(strtolower($gender), ['male', 'female'])) {
            throw new \InvalidArgumentException(sprintf('Invalid gender "%s" provided for tournament simulation. Allowed values are "male" or "female".', $gender));
        }
        $this->gender = strtolower($gender);
    }

    public function getGender(): string
    {
        return $this->gender;
    }
}