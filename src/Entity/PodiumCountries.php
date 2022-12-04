<?php

namespace App\Entity;

use App\Repository\PodiumCountriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PodiumCountriesRepository::class)]
class PodiumCountries
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'podiumCountries')]
    private ?CountriesTeams $first_place = null;

    #[ORM\ManyToOne(inversedBy: 'podiumCountries')]
    private ?CountriesTeams $second_place = null;

    #[ORM\ManyToOne(inversedBy: 'podiumCountries')]
    private ?CountriesTeams $third_place = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'podium_countrie', targetEntity: BetPodium::class)]
    private Collection $betPodia;

    public function __construct()
    {
        $this->betPodia = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstPlace(): ?CountriesTeams
    {
        return $this->first_place;
    }

    public function setFirstPlace(?CountriesTeams $first_place): self
    {
        $this->first_place = $first_place;

        return $this;
    }

    public function getSecondPlace(): ?CountriesTeams
    {
        return $this->second_place;
    }

    public function setSecondPlace(?CountriesTeams $second_place): self
    {
        $this->second_place = $second_place;

        return $this;
    }

    public function getThirdPlace(): ?CountriesTeams
    {
        return $this->third_place;
    }

    public function setThirdPlace(?CountriesTeams $third_place): self
    {
        $this->third_place = $third_place;

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

    /**
     * @return Collection<int, BetPodium>
     */
    public function getBetPodia(): Collection
    {
        return $this->betPodia;
    }

    public function addBetPodium(BetPodium $betPodium): self
    {
        if (!$this->betPodia->contains($betPodium)) {
            $this->betPodia->add($betPodium);
            $betPodium->setPodiumCountrie($this);
        }

        return $this;
    }

    public function removeBetPodium(BetPodium $betPodium): self
    {
        if ($this->betPodia->removeElement($betPodium)) {
            // set the owning side to null (unless already changed)
            if ($betPodium->getPodiumCountrie() === $this) {
                $betPodium->setPodiumCountrie(null);
            }
        }

        return $this;
    }
}
