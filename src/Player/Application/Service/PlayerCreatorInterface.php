<?php

namespace App\Player\Application\Service;

use App\Player\Application\Command\CreatePlayerCommand;
use App\Player\Domain\Entity\Player;

interface PlayerCreatorInterface
{
    public function create(CreatePlayerCommand $command): Player;
}