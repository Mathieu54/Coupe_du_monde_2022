<?php

namespace App\Entity;

use App\Repository\BetQualificationCountriesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BetQualificationCountriesRepository::class)]
class BetQualificationCountries
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'betQualificationCountries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CountriesTeams $countrie_1 = null;

    #[ORM\ManyToOne(inversedBy: 'betQualificationCountries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CountriesTeams $countrie_2 = null;

    #[ORM\ManyToOne(inversedBy: 'betQualificationCountries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QualificationCountries $qualification_countries = null;

    #[ORM\ManyToOne(inversedBy: 'betQualificationCountries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

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

    public function getQualificationCountries(): ?QualificationCountries
    {
        return $this->qualification_countries;
    }

    public function setQualificationCountries(?QualificationCountries $qualification_countries): self
    {
        $this->qualification_countries = $qualification_countries;

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
}
