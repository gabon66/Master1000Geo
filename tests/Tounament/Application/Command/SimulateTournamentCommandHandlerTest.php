<?php

namespace App\Tests\Tounament\Application\Command;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Player\Domain\ValueObject\Gender;
use App\Tournament\Application\Command\SimulateTournamentCommand;
use App\Tournament\Application\Command\SimulateTournamentCommandHandler;
use App\Tournament\Application\Service\CheckDopingPlayersService;
use App\Tournament\Application\Service\MatchService;
use App\Tournament\Application\Service\MatchWinner\MatchWinnerService;
use App\Tournament\Application\Service\TournamentService;
use App\Tournament\Application\Service\WinnerPointsService;
use App\Tournament\Domain\Entity\Tournament;
use PHPUnit\Framework\TestCase;

class SimulateTournamentCommandHandlerTest extends TestCase
{
    private $playerRepository;
    private $matchWinnerService;
    private $winnerPointsService;
    private $matchService;
    private $tournamentService;
    private $checkDopingPlayersService;
    private $commandHandler;

    protected function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepositoryInterface::class); // Using TournamentRepositoryInterface as PlayerRepositoryInterface
        $this->matchWinnerService = $this->createMock(MatchWinnerService::class);
        $this->winnerPointsService = $this->createMock(WinnerPointsService::class);
        $this->matchService = $this->createMock(MatchService::class);
        $this->tournamentService = $this->createMock(TournamentService::class);
        $this->checkDopingPlayersService = $this->createMock(CheckDopingPlayersService::class);

        $this->commandHandler = new SimulateTournamentCommandHandler(
            $this->playerRepository,
            $this->matchWinnerService,
            $this->winnerPointsService,
            $this->matchService,
            $this->tournamentService,
            $this->checkDopingPlayersService
        );
    }

    public function testHandleSimulatesTournamentAndReturnsWinner(): void
    {
        // 1. Crear jugadores de prueba
        $player1 = new Player();
        $player2 = new Player();
        $player3 = new Player();
        $player4 = new Player();

        $players = [$player1, $player2, $player3, $player4];
        $gender = 'male';
        $genderVO = new Gender($gender);

        // 2. Configurar mocks
        $this->playerRepository->expects($this->once())
            ->method('findBy')
            ->with(['gender' => $genderVO])
            ->willReturn($players);

        $tournament = new Tournament();
        $this->tournamentService->expects($this->once())
            ->method('createAndSaveTournament')
            ->with($gender)
            ->willReturn($tournament);

        // Simular los resultados de los partidos (player1 gana vs player2, player3 gana vs player4, luego player1 gana vs player3)
        $this->matchWinnerService->expects($this->exactly(3))
            ->method('determineWinner')
            ->willReturnOnConsecutiveCalls($player1, $player3, $player1);

        // Simular no doping
        $this->checkDopingPlayersService->expects($this->exactly(3))
            ->method('check')
            ->willReturnArgument(0); // El ganador no da positivo

        // Asegurar que se guardan los partidos y se otorgan puntos
        $this->matchService->expects($this->exactly(3))
            ->method('saveMatch');
        $this->winnerPointsService->expects($this->exactly(3))
            ->method('addPoints');

        // Asegurar que se setea el ganador del torneo
        $this->tournamentService->expects($this->once())
            ->method('setTournamentWinner')
            ->with($tournament, $player1);

        // 3. Crear el comando
        $command = new SimulateTournamentCommand($gender);

        // 4. Ejecutar el handler
        $winner = $this->commandHandler->__invoke($command);

        // 5. Assert el resultado
        $this->assertSame($player1, $winner);
    }

    public function testHandleThrowsExceptionIfLessThanTwoPlayers(): void
    {
        $gender = 'female';
        $genderVO = new Gender($gender);
        $this->playerRepository->expects($this->once())
            ->method('findBy')
            ->with(['gender' => $genderVO])
            ->willReturn([new Player()]); // Solo un jugador

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Not enough %s players to simulate a tournament.', $gender));

        $command = new SimulateTournamentCommand($gender);
        $this->commandHandler->__invoke($command);
    }
}