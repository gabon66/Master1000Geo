<?php

namespace App\Tests\Tounament\Application\Query;

use App\Player\Domain\ValueObject\Gender;
use App\Tournament\Application\Presenter\TournamentPresenter;
use App\Tournament\Application\Query\GetTournamentsQuery;
use App\Tournament\Application\Query\GetTournamentsQueryHandler;
use App\Tournament\Domain\Entity\Tournament;
use App\Tournament\Domain\Repository\TournamentRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetTournamentsQueryHandlerTest extends TestCase
{
    public function testHandleReturnsFormattedTournaments(): void
    {
        // 1. Crear mocks de las dependencias
        $tournamentRepository = $this->createMock(TournamentRepositoryInterface::class);
        $tournamentPresenter = $this->createMock(TournamentPresenter::class);

        // 2. Crear datos de prueba (objetos Tournament)
        $tournament1 = new Tournament();
        $tournament1->setName('Tournament A');
        $tournament1->setStartDate(new \DateTimeImmutable('2025-05-15'));

        $tournament2 = new Tournament();
        $tournament2->setName('Tournament B');
        $tournament2->setStartDate(new \DateTimeImmutable('2025-05-20'));


        $tournaments = [$tournament1, $tournament2];

        // Configurar el comportamiento esperado del mock del repositorio
        $tournamentRepository->expects($this->once())
            ->method('findAllByCriteria')
            ->with(null, null, null) // Sin filtros para esta prueba
            ->willReturn($tournaments);

        // Configurar el comportamiento esperado del mock del presenter
        $formattedTournaments = [
            ['id' => 1, 'name' => 'Tournament A', 'startDate' => '2025-05-15 00:00:00', /* ... */],
            ['id' => 2, 'name' => 'Tournament B', 'startDate' => '2025-05-20 00:00:00', /* ... */],
        ];
        $tournamentPresenter->expects($this->once())
            ->method('presentCollection')
            ->with($tournaments)
            ->willReturn($formattedTournaments);

        // 3. Crear la instancia del handler
        $handler = new GetTournamentsQueryHandler($tournamentRepository, $tournamentPresenter);

        // 4. Crear la instancia de la query
        $query = new GetTournamentsQuery(null, null, null);

        // 5. Ejecutar el handler
        $result = $handler($query);

        // 6. Afirmar el resultado
        $this->assertEquals($formattedTournaments, $result);
    }

    public function testHandleFiltersTournamentsByGender(): void
    {
        // 1. Crear mocks de las dependencias
        $tournamentRepository = $this->createMock(TournamentRepositoryInterface::class);
        $tournamentPresenter = $this->createMock(TournamentPresenter::class);

        // 2. Crear datos de prueba (objetos Tournament)
        $tournament1 = new Tournament();
        $tournament1->setName('Male Tournament A');
        $tournament1->setStartDate(new \DateTimeImmutable('2025-05-15'));
        $tournament1->setGenderTournament(new Gender('male'));

        $tournament2 = new Tournament();
        $tournament2->setName('Female Tournament B');
        $tournament2->setStartDate(new \DateTimeImmutable('2025-05-20'));
        $tournament2->setGenderTournament(new Gender('female'));

        $allTournaments = [$tournament1, $tournament2];
        $maleTournaments = [$tournament1];

        // 3. Configurar el comportamiento esperado del mock del repositorio
        $tournamentRepository->expects($this->once())
            ->method('findAllByCriteria')
            ->with('male', null, null) // Espera la cadena 'male' como primer argumento
            ->willReturn($maleTournaments);

        // 4. Configurar el comportamiento esperado del mock del presenter
        $formattedTournaments = [
            ['id' => 1, 'name' => 'Male Tournament A', 'startDate' => '2025-05-15 00:00:00', /* ... */],
        ];
        $tournamentPresenter->expects($this->once())
            ->method('presentCollection')
            ->with($maleTournaments)
            ->willReturn($formattedTournaments);

        // 5. Crear la instancia del handler
        $handler = new GetTournamentsQueryHandler($tournamentRepository, $tournamentPresenter);

        // 6. Crear la instancia de la query con filtro de género
        $query = new GetTournamentsQuery('male', null, null);

        // 7. Ejecutar el handler
        $result = $handler($query);

        // 8. Afirmar el resultado
        $this->assertEquals($formattedTournaments, $result);
    }

}