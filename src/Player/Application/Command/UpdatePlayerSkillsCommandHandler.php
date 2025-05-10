<?php

namespace App\Player\Application\Command;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Player\Domain\ValueObject\Skill\Ability;
use App\Player\Domain\ValueObject\Skill\Reaction;
use App\Player\Domain\ValueObject\Skill\Strength;
use App\Player\Domain\ValueObject\Skill\Velocity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
class UpdatePlayerSkillsCommandHandler
{
    private PlayerRepositoryInterface $playerRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PlayerRepositoryInterface $playerRepository, EntityManagerInterface $entityManager)
    {
        $this->playerRepository = $playerRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(UpdatePlayerSkillsCommand $command): Player
    {
        $playerId = $command->getPlayerId();
        $player = $this->playerRepository->find($playerId);

        if (!$player instanceof Player) {
            throw new NotFoundHttpException(sprintf('Player with ID "%s" not found.', $playerId));
        }

        if (null !== $command->getStrength()) {
            $player->setStrength(new Strength($command->getStrength()));
        }

        if (null !== $command->getAbility()) {
            $player->setAbility(new Ability($command->getAbility()));
        }

        if (null !== $command->getVelocity()) {
            $player->setVelocity(new Velocity($command->getVelocity()));
        }

        if (null !== $command->getReaction()) {
            $player->setReaction(new Reaction($command->getReaction()));
        }

        $this->entityManager->flush();

        return $player;
    }
}