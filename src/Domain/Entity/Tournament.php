<?php

namespace App\Domain\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
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

    #[ORM\Column(type: 'integer')]
    private ?int $endDate = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $genderTournament = null;

    #[ORM\ManyToMany(targetEntity: Player::class, inversedBy: 'tournaments')]
    #[ORM\JoinTable(name: 'tournament_players')]
    #[ORM\JoinColumn(name: 'tournament_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'player_id', referencedColumnName: 'id')]
    private Collection $players;

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: MatchGame::class)]
    private Collection $matches;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->matches = new ArrayCollection();
    }

    // Getters y setters para todas las propiedades, incluyendo para la colección de jugadores y partidos

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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?int
    {
        return $this->endDate;
    }

    public function setEndDate(int $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function isGenderTournament(): ?bool
    {
        return $this->genderTournament;
    }

    public function setGenderTournament(bool $genderTournament): self
    {
        $this->genderTournament = $genderTournament;

        return $this;
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