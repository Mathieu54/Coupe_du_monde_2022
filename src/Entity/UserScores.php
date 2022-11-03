<?php

namespace App\Entity;

use App\Repository\UserScoresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserScoresRepository::class)]
class UserScores
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'userScores', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $scores = null;

    #[ORM\Column]
    private ?int $bet_lose = null;

    #[ORM\Column]
    private ?int $bet_win = null;

    #[ORM\Column]
    private ?int $bet_win_bonus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getScores(): ?int
    {
        return $this->scores;
    }

    public function setScores(int $scores): self
    {
        $this->scores = $scores;

        return $this;
    }

    public function getBetLose(): ?int
    {
        return $this->bet_lose;
    }

    public function setBetLose(int $bet_lose): self
    {
        $this->bet_lose = $bet_lose;

        return $this;
    }

    public function getBetWin(): ?int
    {
        return $this->bet_win;
    }

    public function setBetWin(int $bet_win): self
    {
        $this->bet_win = $bet_win;

        return $this;
    }

    public function getBetWinBonus(): ?int
    {
        return $this->bet_win_bonus;
    }

    public function setBetWinBonus(int $bet_win_bonus): self
    {
        $this->bet_win_bonus = $bet_win_bonus;

        return $this;
    }
}
