<?php

namespace App\Tournament\Domain\Service;

use App\Player\Domain\Entity\Player;

interface WinnerPointsServiceInterface
{
    public function addPoints(Player $winner): void;
}