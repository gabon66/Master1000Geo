<?php

namespace App\Tournament\Domain\Service\MatchWinner;

use App\Player\Domain\Entity\Player;

interface MatchWinnerStrategyInterface
{
    public function determineWinner(Player $player1, Player $player2): Player;

    function getLuck():int;
}