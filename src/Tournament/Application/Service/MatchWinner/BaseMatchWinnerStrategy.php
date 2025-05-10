<?php

namespace App\Tournament\Application\Service\MatchWinner;

use App\Player\Domain\Entity\Player;
use App\Tournament\Domain\Enum\TournamentRules;
use App\Tournament\Domain\Service\MatchWinner\MatchWinnerStrategyInterface;

class BaseMatchWinnerStrategy implements MatchWinnerStrategyInterface
{
    public function determineWinner(Player $player1, Player $player2): Player
    {
        // Comparación basada en la habilidad (común para ambos géneros)
        $ability1 = $player1->getAbility()->getValue() + $this->getLuck();
        $ability2 = $player2->getAbility()->getValue() + $this->getLuck();

        return $ability1 >= $ability2 ? $player1 : $player2;
    }

    public function getLuck(): int
    {
        return mt_rand(1, TournamentRules::MAX_LUCK_FACTOR->value);
    }
}