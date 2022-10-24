<?php

namespace App\Entity;

use App\Repository\MatchesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchesRepository::class)]
class Matches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'matches')]
    private ?CountriesTeams $countrie_1 = null;

    #[ORM\ManyToOne(inversedBy: 'matches')]
    private ?CountriesTeams $countrie_2 = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $score_countrie_1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $score_countrie_2 = null;

    #[ORM\Column]
    private ?int $type_match = null;

    #[ORM\OneToMany(mappedBy: 'matches', targetEntity: BetUser::class, orphanRemoval: true)]
    private Collection $betUsers;

    public function __construct()
    {
        $this->betUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountrie1(): ?CountriesTeams
    {
        return $this->countrie_1;
    }

    public function setCountrie1(?CountriesTeams $countrie_1): self
    {
        $this->countrie_1 = $countrie_1;

        return $this;
    }

    public function getCountrie2(): ?CountriesTeams
    {
        return $this->countrie_2;
    }

    public function setCountrie2(?CountriesTeams $countrie_2): self
    {
        $this->countrie_2 = $countrie_2;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getScoreCountrie1(): ?int
    {
        return $this->score_countrie_1;
    }

    public function setScoreCountrie1(?int $score_countrie_1): self
    {
        $this->score_countrie_1 = $score_countrie_1;

        return $this;
    }

    public function getScoreCountrie2(): ?int
    {
        return $this->score_countrie_2;
    }

    public function setScoreCountrie2(?int $score_countrie_2): self
    {
        $this->score_countrie_2 = $score_countrie_2;

        return $this;
    }

    public function getTypeMatch(): ?int
    {
        return $this->type_match;
    }

    public function setTypeMatch(int $type_match): self
    {
        $this->type_match = $type_match;

        return $this;
    }

    /**
     * @return Collection<int, BetUser>
     */
    public function getBetUsers(): Collection
    {
        return $this->betUsers;
    }

    public function addBetUser(BetUser $betUser): self
    {
        if (!$this->betUsers->contains($betUser)) {
            $this->betUsers->add($betUser);
            $betUser->setMatches($this);
        }

        return $this;
    }

    public function removeBetUser(BetUser $betUser): self
    {
        if ($this->betUsers->removeElement($betUser)) {
            // set the owning side to null (unless already changed)
            if ($betUser->getMatches() === $this) {
                $betUser->setMatches(null);
            }
        }

        return $this;
    }
}
