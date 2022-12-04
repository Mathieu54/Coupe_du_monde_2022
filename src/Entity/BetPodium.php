<?php

namespace App\Entity;

use App\Repository\BetPodiumRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BetPodiumRepository::class)]
class BetPodium
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'betPodia')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'betPodia')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PodiumCountries $podium_countrie = null;

    #[ORM\ManyToOne(inversedBy: 'betPodia')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CountriesTeams $first_countrie_user = null;

    #[ORM\ManyToOne(inversedBy: 'betPodia')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CountriesTeams $second_countrie_user = null;

    #[ORM\ManyToOne(inversedBy: 'betPodia')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CountriesTeams $third_countrie_user = null;

    #[ORM\Column]
    private ?bool $calculation = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPodiumCountrie(): ?PodiumCountries
    {
        return $this->podium_countrie;
    }

    public function setPodiumCountrie(?PodiumCountries $podium_countrie): self
    {
        $this->podium_countrie = $podium_countrie;

        return $this;
    }

    public function getFirstCountrieUser(): ?CountriesTeams
    {
        return $this->first_countrie_user;
    }

    public function setFirstCountrieUser(?CountriesTeams $first_countrie_user): self
    {
        $this->first_countrie_user = $first_countrie_user;

        return $this;
    }

    public function getSecondCountrieUser(): ?CountriesTeams
    {
        return $this->second_countrie_user;
    }

    public function setSecondCountrieUser(?CountriesTeams $second_countrie_user): self
    {
        $this->second_countrie_user = $second_countrie_user;

        return $this;
    }

    public function getThirdCountrieUser(): ?CountriesTeams
    {
        return $this->third_countrie_user;
    }

    public function setThirdCountrieUser(?CountriesTeams $third_countrie_user): self
    {
        $this->third_countrie_user = $third_countrie_user;

        return $this;
    }

    public function isCalculation(): ?bool
    {
        return $this->calculation;
    }

    public function setCalculation(bool $calculation): self
    {
        $this->calculation = $calculation;

        return $this;
    }
}
