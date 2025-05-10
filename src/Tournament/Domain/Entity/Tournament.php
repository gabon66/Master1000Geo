<?php

namespace App\Tournament\Domain\Entity;

use App\Player\Domain\Entity\Player;
use App\Player\Domain\ValueObject\Gender;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tournaments')]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: 'gender', length: 10, nullable: true)] // Usamos nuestro tipo 'gender'
    private ?Gender $genderTournament = null;

    #[ORM\ManyToMany(targetEntity: Player::class, inversedBy: 'tournaments')]
    #[ORM\JoinTable(name: 'tournament_players')]
    #[ORM\JoinColumn(name: 'tournament_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'player_id', referencedColumnName: 'id')]
    private Collection $players;

    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(name: 'winner_id', referencedColumnName: 'id', nullable: true)]
    private ?Player $winner = null; // Nuevo campo para el ganador


    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->matches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getWinner(): ?Player
    {
        return $this->winner;
    }

    public function setWinner(?Player $winner): void
    {
        $this->winner = $winner;
    }


    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }


    public function getGenderTournament(): ?Gender
    {
        return $this->genderTournament;
    }

    public function setGenderTournament(?Gender $genderTournament): void
    {
        $this->genderTournament = $genderTournament;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        $this->players->removeElement($player);

        return $this;
    }

    /**
     * @return Collection<int, MatchGame>
     */
    public function getMatches(): Collection
    {
        return $this->matches;
    }

    public function addMatch(MatchGame $match): self
    {
        if (!$this->matches->contains($match)) {
            $this->matches->add($match);
            $match->setTournament($this);
        }

        return $this;
    }

    public function removeMatch(MatchGame $match): self
    {
        if ($this->matches->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getTournament() === $this) {
                $match->setTournament(null);
            }
        }

        return $this;
    }
}