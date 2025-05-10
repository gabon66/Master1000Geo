<?php

namespace App\Player\Application\Command;

class UpdatePlayerSkillsCommand
{
    private int $playerId;
    private ?int $strength;
    private ?int $velocity;
    private ?int $reaction;
    private ?int $ability;

    public function __construct(int $playerId,?int $ability, ?int $strength, ?int $velocity, ?int $reaction)
    {
        $this->playerId = $playerId;
        $this->ability = $ability;
        $this->strength = $strength;
        $this->velocity = $velocity;
        $this->reaction = $reaction;
    }

    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function getVelocity(): ?int
    {
        return $this->velocity;
    }

    public function getAbility(): ?int
    {
        return $this->ability;
    }

    public function getReaction(): ?int
    {
        return $this->reaction;
    }
}