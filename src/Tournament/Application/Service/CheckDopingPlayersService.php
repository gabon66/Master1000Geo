<?php

namespace App\Tournament\Application\Service;

use App\Player\Domain\Entity\Player;
use App\Tournament\Domain\Enum\TournamentRules;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Servicio encargado de validar doping  para 1 de los 2 jugadores
 * En caso de dar negativo todo sigue igual.
 * Pero si da positivo no solo pierde el partido  , tambien se le descuentan los puntos acumulados como castigo
 */
class CheckDopingPlayersService
{
    private EntityManagerInterface $entityManager;
    private float $dopingProbability;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->dopingProbability = TournamentRules::DOPING_PROBABILITY_PERCENTAGE->value / 100;
    }

    public function check(Player $winner, Player $loser): Player
    {
        // Simular la selección aleatoria para la prueba de doping (solo a uno)
        $playersToTest = [$winner, $loser];
        shuffle($playersToTest);
        $playerToTest = $playersToTest[0]; // Testeamos al primero después de barajar

        // Simular el resultado del doping
        $dopingResult = (mt_rand(1, 100) <= ($this->dopingProbability * 100)); // Probabilidad en porcentaje

        if ($dopingResult) {
            $playerToTest->setPoints(0);
            $this->entityManager->flush();
            if ($playerToTest === $winner) {
                return $loser;
            } else {
                return $winner; // El ganador original se mantiene
            }
        }
        return $winner; // No hubo doping positivo o el perdedor dio positivo, el ganador se mantiene
    }
}