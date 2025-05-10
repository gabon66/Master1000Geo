<?php

namespace App\Player\Application\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePlayerCommand
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public ?string $name;

    #[Assert\NotBlank]
    public ?string $gender;

    public ?int $strength;

    public ?int $velocity;

    public ?int $reaction;
    public ?int $ability;

    public ?int $age;

    public function __construct(
        ?string $name,
        ?string $gender,
        ?int    $strength,
        ?int    $velocity,
        ?int    $reaction,
        ?int    $ability,
        ?int    $age
    )
    {
        $this->name = $name;
        $this->gender = $gender;
        $this->age = $age;
        $this->strength = $strength;
        $this->velocity = $velocity;
        $this->reaction = $reaction;
        $this->ability = $ability;
    }
}