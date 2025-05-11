<?php

namespace App\Tournament\Application\Query;

class GetTournamentsQuery
{
    private ?string $gender;
    private ?string $startDate;
    private ?string $endDate;

    public function __construct(?string $gender, ?string $startDate, ?string $endDate)
    {
        $this->gender = $gender;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }
}