<?php

namespace App\Player\Application\Command;

class DeletePlayerCommand
{
    private int $playerId;

    public function __construct(int $playerId)
    {
        $this->playerId = $playerId;
    }

    public function getPlayerId(): int
    {
        return $this->playerId;
    }
}