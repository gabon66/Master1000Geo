<?php

namespace App\Player\Domain\Entity;

use App\Player\Domain\ValueObject\Age;
use App\Player\Domain\ValueObject\Gender;
use App\Player\Domain\ValueObject\Skill\Reaction;
use App\Player\Domain\ValueObject\Skill\Strength;
use App\Player\Domain\ValueObject\Skill\Velocity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'players')]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['player_list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['player_list'])]
    private ?string $name = null;

    #[ORM\Column(type: 'gender', length: 10, nullable: true)] // Usamos nuestro tipo 'gender'
    #[Groups(['player_list'])]
    private ?Gender $gender = null;

    #[ORM\Column(type: 'strength',nullable: true)]
    #[Groups(['player_list', 'player_details', 'player_create', 'player_update'])]
    private ?Strength $strength = null;

    #[ORM\Column(type: 'velocity',nullable: true)]
    #[Groups(['player_list', 'player_details', 'player_create', 'player_update'])]
    private ?Velocity $velocity = null;

    #[ORM\Column(type: 'reaction',nullable: true)]
    #[Groups(['player_list', 'player_details', 'player_create', 'player_update'])]
    private ?Reaction $reaction = null;

    #[ORM\Column(type: 'age')]
    #[Groups(['player_list'])]
    private ?Age $age = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['player_list'])]
    private ?int $points = 0;

    // Getters y setters para todas las propiedades

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): void
    {
        $this->gender = $gender;
    }

    public function getStrength(): ?Strength
    {
        return $this->strength;
    }

    public function setStrength(?Strength $strength): void
    {
        $this->strength = $strength;
    }

    public function getVelocity(): ?Velocity
    {
        return $this->velocity;
    }

    public function setVelocity(?Velocity $velocity): void
    {
        $this->velocity = $velocity;
    }

    public function getReaction(): ?Reaction
    {
        return $this->reaction;
    }

    public function setReaction(?Reaction $reaction): void
    {
        $this->reaction = $reaction;
    }

    public function getAge(): ?Age
    {
        return $this->age;
    }

    public function setAge(?Age $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }
}