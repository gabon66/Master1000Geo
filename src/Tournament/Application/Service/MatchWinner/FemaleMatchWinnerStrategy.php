<?php

namespace App\Tournament\Application\Service\MatchWinner;

use App\Player\Domain\Entity\Player;

class FemaleMatchWinnerStrategy extends BaseMatchWinnerStrategy
{
    public function determineWinner(Player $player1, Player $player2): Player
    {
        $winner = parent::determineWinner($player1, $player2);

        /**
         * Comparación adicional para mujeres: reacción
         */
        if ($player1 === $winner) {
            $score1 = $player1->getReaction()->getValue();
            $score2 = $player2->getReaction()->getValue();
            return $score1 >= $score2 ? $player1 : $player2;
        } else {
            $score1 = $player1->getReaction()->getValue();
            $score2 = $player2->getReaction()->getValue();
            return $score2 >= $score1 ? $player2 : $player1;
        }
    }
}