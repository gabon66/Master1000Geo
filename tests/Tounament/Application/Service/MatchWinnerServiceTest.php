<?php

namespace App\Tests\Tounament\Application\Service;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\ValueObject\Skill\Ability;
use App\Player\Domain\ValueObject\Skill\Reaction;
use App\Player\Domain\ValueObject\Skill\Velocity;
use App\Tournament\Application\Service\MatchWinner\BaseMatchWinnerStrategy;
use App\Tournament\Application\Service\MatchWinner\FemaleMatchWinnerStrategy;
use App\Tournament\Application\Service\MatchWinner\MaleMatchWinnerStrategy;
use App\Tournament\Application\Service\MatchWinner\MatchWinnerService;
use PHPUnit\Framework\TestCase;

/**
 * Test encargado de validar toda la logica del MatchWinnerService
 * Validar que tipo de estrategia usa para male o female y
 * como compara los valores (skills)  para cada caso.
 */
class MatchWinnerServiceTest extends TestCase
{
    private $baseStrategy;
    private $maleStrategy;
    private $femaleStrategy;
    private $matchWinnerService;

    const LOW_SKILLS_VALUES=10;
    const HIGH_SKILLS_VALUES=90;

    protected function setUp(): void
    {
        $this->baseStrategy = $this->createMock(BaseMatchWinnerStrategy::class);
        $this->maleStrategy = $this->createMock(MaleMatchWinnerStrategy::class);
        $this->femaleStrategy = $this->createMock(FemaleMatchWinnerStrategy::class);
        $this->matchWinnerService = new MatchWinnerService(
            $this->baseStrategy,
            $this->maleStrategy,
            $this->femaleStrategy
        );
    }

    public function testDetermineWinnerMaleStrategyCalled(): void
    {
        $player1 = new Player();
        $player2 = new Player();
        $expectedWinner = $player1;

        $this->maleStrategy->expects($this->once())
            ->method('determineWinner')
            ->with($player1, $player2)
            ->willReturn($expectedWinner);

        $this->femaleStrategy->expects($this->never())
            ->method('determineWinner');
        $this->baseStrategy->expects($this->never())
            ->method('determineWinner');

        $winner = $this->matchWinnerService->determineWinner($player1, $player2, 'male');
        $this->assertSame($expectedWinner, $winner);
    }

    public function testDetermineWinnerFemaleStrategyCalled(): void
    {
        $player1 = new Player();
        $player2 = new Player();
        $expectedWinner = $player2;

        $this->femaleStrategy->expects($this->once())
            ->method('determineWinner')
            ->with($player1, $player2)
            ->willReturn($expectedWinner);

        $this->maleStrategy->expects($this->never())
            ->method('determineWinner');
        $this->baseStrategy->expects($this->never())
            ->method('determineWinner');

        $winner = $this->matchWinnerService->determineWinner($player1, $player2, 'female');
        $this->assertSame($expectedWinner, $winner);
    }

    public function testDetermineWinnerMaleStrategyCalledWithUppercaseGender(): void
    {
        $player1 = new Player();
        $player2 = new Player();
        $expectedWinner = $player1;

        $this->maleStrategy->expects($this->once())
            ->method('determineWinner')
            ->with($player1, $player2)
            ->willReturn($expectedWinner);

        $this->femaleStrategy->expects($this->never())
            ->method('determineWinner');
        $this->baseStrategy->expects($this->never())
            ->method('determineWinner');

        $winner = $this->matchWinnerService->determineWinner($player1, $player2, 'MALE');
        $this->assertSame($expectedWinner, $winner);
    }

    public function testDetermineWinnerFemaleStrategyCalledWithMixedCaseGender(): void
    {
        $player1 = new Player();
        $player2 = new Player();
        $expectedWinner = $player2;

        $this->femaleStrategy->expects($this->once())
            ->method('determineWinner')
            ->with($player1, $player2)
            ->willReturn($expectedWinner);

        $this->maleStrategy->expects($this->never())
            ->method('determineWinner');
        $this->baseStrategy->expects($this->never())
            ->method('determineWinner');

        $winner = $this->matchWinnerService->determineWinner($player1, $player2, 'FeMaLe');
        $this->assertSame($expectedWinner, $winner);
    }

    public function testDetermineWinnerDefaultsToFemaleForUnknownGender(): void
    {
        $player1 = new Player();
        $player2 = new Player();
        $expectedWinner = $player2;

        $this->femaleStrategy->expects($this->once())
            ->method('determineWinner')
            ->with($player1, $player2)
            ->willReturn($expectedWinner);

        $this->maleStrategy->expects($this->never())
            ->method('determineWinner');
        $this->baseStrategy->expects($this->never())
            ->method('determineWinner');

        $winner = $this->matchWinnerService->determineWinner($player1, $player2, 'other');
        $this->assertSame($expectedWinner, $winner);
    }

    public function testDetermineWinnerMaleStrategyDeterminesWinnerBasedOnPlayerAttributes(): void
    {
        // 1. Crear jugadores con atributos específicos
        $player1 = new Player();
        $player1->setAbility(new Ability(self::HIGH_SKILLS_VALUES));
        $player1->setVelocity(new Velocity(self::HIGH_SKILLS_VALUES));


        $player2 = new Player();
        $player2->setAbility(new Ability(self::LOW_SKILLS_VALUES));
        $player1->setVelocity(new Velocity(self::LOW_SKILLS_VALUES));

        $expectedWinner = $player1;

        // 2. Configurar el mock de la estrategia masculina para simular la lógica
        $this->maleStrategy->expects($this->once())
            ->method('determineWinner')
            ->with($this->equalTo($player1), $this->equalTo($player2))
            ->willReturnCallback(function (Player $p1, Player $p2) {
                // Simulación de la lógica de la estrategia masculina con suerte cero
                if ($p1->getAbility() > $p2->getAbility()) {
                    return $p1;
                }
                return $p2;
            });

        // 3. Asegurar que las otras estrategias no se llamen
        $this->femaleStrategy->expects($this->never())
            ->method('determineWinner');
        $this->baseStrategy->expects($this->never())
            ->method('determineWinner');

        // 4. Llamar al servicio
        $winner = $this->matchWinnerService->determineWinner($player1, $player2, 'male');

        // 5. Verificar que el ganador es el esperado
        $this->assertSame($expectedWinner, $winner);
    }

    public function testDetermineWinnerFemaleStrategyDeterminesWinnerBasedOnSkillReaction(): void
    {
        // 1. Crear jugadoras con atributos específicos
        $player1 = new Player();
        $player1->setReaction(new Reaction(self::HIGH_SKILLS_VALUES)); // Alta reacción

        $player2 = new Player();
        $player2->setReaction(new Reaction(self::LOW_SKILLS_VALUES)); // Baja reacción

        $expectedWinner = $player1;

        // 2. Configurar el mock de la estrategia femenina para simular la lógica
        $this->femaleStrategy->expects($this->once())
            ->method('determineWinner')
            ->with($this->equalTo($player1), $this->equalTo($player2))
            ->willReturnCallback(function (Player $p1, Player $p2) {
                // Simulación de la lógica de la estrategia femenina basada en la reacción
                if ($p1->getReaction() > $p2->getReaction()) {
                    return $p1;
                }
                return $p2;
            });

        // 3. Asegurar que las otras estrategias no se llamen
        $this->maleStrategy->expects($this->never())
            ->method('determineWinner');
        $this->baseStrategy->expects($this->never())
            ->method('determineWinner');

        // 4. Llamar al servicio con género 'female'
        $winner = $this->matchWinnerService->determineWinner($player1, $player2, 'female');

        // 5. Verificar que la ganadora es la esperada
        $this->assertSame($expectedWinner, $winner);
    }

}