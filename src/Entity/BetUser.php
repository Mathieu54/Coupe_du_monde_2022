<?php

namespace App\Entity;

use App\Repository\BetUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BetUserRepository::class)]
class BetUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'betUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Matches $matches = null;

    #[ORM\Column]
    private ?int $score_countrie_1 = null;

    #[ORM\Column]
    private ?int $score_countrie_2 = null;

    #[ORM\ManyToOne(inversedBy: 'betUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $calculate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatches(): ?Matches
    {
        return $this->matches;
    }

    public function setMatches(?Matches $matches): self
    {
        $this->matches = $matches;

        return $this;
    }

    public function getScoreCountrie1(): ?int
    {
        return $this->score_countrie_1;
    }

    public function setScoreCountrie1(int $score_countrie_1): self
    {
        $this->score_countrie_1 = $score_countrie_1;

        return $this;
    }

    public function getScoreCountrie2(): ?int
    {
        return $this->score_countrie_2;
    }

    public function setScoreCountrie2(int $score_countrie_2): self
    {
        $this->score_countrie_2 = $score_countrie_2;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isCalculate(): ?bool
    {
        return $this->calculate;
    }

    public function setCalculate(bool $calculate): self
    {
        $this->calculate = $calculate;

        return $this;
    }
}
