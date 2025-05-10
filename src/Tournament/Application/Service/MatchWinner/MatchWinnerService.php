<?php

namespace App\Tournament\Application\Service\MatchWinner;


use App\Player\Domain\Entity\Player;

class MatchWinnerService
{
    private BaseMatchWinnerStrategy $baseStrategy;
    private MaleMatchWinnerStrategy $maleStrategy;
    private FemaleMatchWinnerStrategy $femaleStrategy;

    public function __construct(BaseMatchWinnerStrategy $baseStrategy,
                                MaleMatchWinnerStrategy $maleStrategy,
                                FemaleMatchWinnerStrategy $femaleStrategy)
    {
        $this->baseStrategy = $baseStrategy;
        $this->maleStrategy = $maleStrategy;
        $this->femaleStrategy = $femaleStrategy;
    }

    public function determineWinner(Player $player1, Player $player2, string $gender): Player
    {
        $gender = strtolower($gender);

        if ($gender === 'male') {
            return $this->maleStrategy->determineWinner($player1, $player2);
        }
        return $this->femaleStrategy->determineWinner($player1, $player2);
    }
}