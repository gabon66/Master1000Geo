<?php

namespace App\Tests\Tounament\Application\Service;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\ValueObject\Gender;
use App\Tournament\Application\Service\TournamentService;
use App\Tournament\Domain\Entity\Tournament;
use App\Tournament\Domain\Repository\TournamentRepositoryInterface;
use PHPUnit\Framework\TestCase;


class TournamentServiceTest extends TestCase
{
    public function testCreateAndSaveTournamentCreatesAndPersistsTournament(): void
    {
        // 1. Crear mock del repositorio
        $tournamentRepository = $this->createMock(TournamentRepositoryInterface::class);

        // 2. Configurar el comportamiento esperado del mock del repositorio
        $tournamentRepository->expects($this->exactly(2)) // Esperamos dos llamadas a save (inicial y después de setear el nombre)
        ->method('save')
            ->with($this->isInstanceOf(Tournament::class));

        // 3. Crear la instancia del servicio
        $tournamentService = new TournamentService($tournamentRepository);

        // 4. Ejecutar el método a probar
        $gender = 'male';
        $tournament = $tournamentService->createAndSaveTournament($gender);

        // 5. Afirmar el resultado
        $this->assertInstanceOf(Tournament::class, $tournament);
        $this->assertEquals(new Gender($gender), $tournament->getGenderTournament());
        $this->assertNotNull($tournament->getStartDate());
        $this->assertStringStartsWith('Master 1000 Edición ', $tournament->getName());
        $this->assertStringEndsWith(' - Male', $tournament->getName());
    }

    public function testSetTournamentWinnerUpdatesTournamentAndPersists(): void
    {
        // 1. Crear mocks
        $tournamentRepository = $this->createMock(TournamentRepositoryInterface::class);
        $tournament = new Tournament();
        $winner = new Player();
        $winner->setName('Novak Djokovic');

        // 2. Configurar el comportamiento esperado del repositorio
        $tournamentRepository->expects($this->once())
            ->method('save')
            ->with($tournament); // Verificamos que se guarda el mismo objeto torneo

        // 3. Crear el servicio
        $tournamentService = new TournamentService($tournamentRepository);

        // 4. Ejecutar el método
        $tournamentService->setTournamentWinner($tournament, $winner);

        // 5. Afirmar el resultado
        $this->assertSame($winner, $tournament->getWinner());
    }
}