<?php

namespace App\Tournament\Application\Command;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Player\Domain\ValueObject\Gender;
use App\Tournament\Application\Service\CheckDopingPlayersService;
use App\Tournament\Application\Service\MatchService;
use App\Tournament\Application\Service\MatchWinner\MatchWinnerService;
use App\Tournament\Application\Service\TournamentService;
use App\Tournament\Application\Service\WinnerPointsService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SimulateTournamentCommandHandler
{
    private PlayerRepositoryInterface $playerRepository;
    private MatchWinnerService $matchWinnerService;
    private WinnerPointsService $winnerPointsService;
    private MatchService $matchService;
    private TournamentService $tournamentService;
    private CheckDopingPlayersService $checkDopingPlayersService;
    public function __construct(
        PlayerRepositoryInterface $playerRepository,
        MatchWinnerService $matchWinnerService,
        WinnerPointsService $winnerPointsService,
        MatchService $matchService,
        TournamentService $tournamentService,
        CheckDopingPlayersService $checkDopingPlayersService
    ) {
        $this->playerRepository = $playerRepository;
        $this->matchWinnerService = $matchWinnerService;
        $this->winnerPointsService = $winnerPointsService;
        $this->matchService = $matchService;
        $this->tournamentService = $tournamentService;
        $this->checkDopingPlayersService = $checkDopingPlayersService;
    }

    public function __invoke(SimulateTournamentCommand $command): ?Player
    {
        $gender = new Gender($command->getGender());

        /**
         * Obtentemos los jugadores
         */
        $players = $this->getPlayersToPlay($gender);

        /**
         * Creamos el torneo
         */
        $tournament = $this->tournamentService->createAndSaveTournament($gender->getValue());


        /**
         * Hacemos los cruces correspondiendientes
         */
        $remainingPlayers = $players;
        while (count($remainingPlayers) > 1) {
            $nextRound = [];
            shuffle($remainingPlayers);
            for ($i = 0; $i < count($remainingPlayers); $i += 2) {
                $player1 = $remainingPlayers[$i];
                $player2 = $remainingPlayers[$i + 1] ?? null; // Usamos el operador null coalescing

                if ($player2) {
                    /**
                     * Determinamos el winner por sus skills
                     */
                    $winner = $this->matchWinnerService->determineWinner($player1, $player2, $gender->getValue());

                    /**
                     * Validamos doping
                     */
                    $finalWinner = $this->checkDopingPlayersService->check($winner, $player1 === $winner ? $player2 : $player1);

                    $this->matchService->saveMatch($player1, $player2, $finalWinner, $tournament);


                    $this->winnerPointsService->addPoints($finalWinner);

                    $nextRound[] = $finalWinner;
                } else {
                    // Si no hay un segundo jugador (número impar), el jugador actual pasa a la siguiente ronda
                    $nextRound[] = $player1;
                    // No llamamos a determineWinner aquí ya que no hay oponente.
                }
            }
            $remainingPlayers = $nextRound;
        }

        $winner = $remainingPlayers[0];

        $this->tournamentService->setTournamentWinner($tournament,$winner);

        /**
         * Retornamos el ganador !!!!
         */
        return $winner;
    }

    private function getPlayersToPlay(Gender $gender):array
    {

        $players = $this->playerRepository->findBy(['gender' => $gender]);

        if (count($players) < 2) {
            throw new \RuntimeException(sprintf('Not enough %s players to simulate a tournament.', $gender));
        }

        /**
         * Si hay un número impar de jugadores, descartar uno aleatoriamente
         */
        if (count($players) % 2 !== 0) {
            $randomIndex = array_rand($players);
            unset($players[$randomIndex]);
            $players = array_values($players);
        }
        return $players;
    }
}