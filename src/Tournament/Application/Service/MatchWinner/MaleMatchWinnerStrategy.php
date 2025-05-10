<?php

namespace App\Tournament\Application\Service\MatchWinner;

use App\Player\Domain\Entity\Player;

class MaleMatchWinnerStrategy extends BaseMatchWinnerStrategy
{
    public function determineWinner(Player $player1, Player $player2): Player
    {
        $winner = parent::determineWinner($player1, $player2);

        /**
         * Comparación adicional para hombres: fuerza y velocidad
         */
        if ($player1 === $winner) {
            $score1 = $player1->getStrength()->getValue() + $player1->getVelocity()->getValue();
            $score2 = $player2->getStrength()->getValue() + $player2->getVelocity()->getValue();
            return $score1 >= $score2 ? $player1 : $player2;
        } else {
            $score1 = $player1->getStrength()->getValue() + $player1->getVelocity()->getValue();
            $score2 = $player2->getStrength()->getValue() + $player2->getVelocity()->getValue();
            return $score2 >= $score1 ? $player2 : $player1;
        }
    }
}