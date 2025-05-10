<?php

namespace App\Tournament\Domain\Enum;

enum TournamentRules: int
{
    /**
     * Representa los puntos ganados por partido
     */
    case MATCH_WIN_POINT = 10;

    /**
     * Representa la probabilidad de dar doping positivo
     */
    case DOPING_PROBABILITY_PERCENTAGE = 20;

    /**
     * Representa el valor máximo del factor de suerte en los partidos
     * Seria de 1 a este valor :
     * Esto se sumaria a la habilidad del jugador
     */
    case MAX_LUCK_FACTOR = 30;

}
