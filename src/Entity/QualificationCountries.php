<?php

namespace App\Entity;

use App\Repository\QualificationCountriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QualificationCountriesRepository::class)]
class QualificationCountries
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'qualificationCountries')]
    private ?CountriesTeams $first_country_res = null;

    #[ORM\ManyToOne(inversedBy: 'qualificationCountries')]
    private ?CountriesTeams $second_country_res = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $type_phase = null;

    #[ORM\OneToMany(mappedBy: 'qualification_countries', targetEntity: BetQualificationCountries::class, orphanRemoval: true)]
    private Collection $betQualificationCountries;

    #[ORM\ManyToOne(inversedBy: 'qualificationCountriesRes')]
    private ?CountriesTeams $countrie_1_eighth = null;

    #[ORM\ManyToOne(inversedBy: 'qualificationCountriesRes2')]
    private ?CountriesTeams $countrie_2_eighth = null;

    #[ORM\ManyToOne(inversedBy: 'qualificationCountriesRes3')]
    private ?CountriesTeams $countrie_3_eighth = null;

    #[ORM\ManyToOne(inversedBy: 'qualificationCountriesRes4')]
    private ?CountriesTeams $countrie_4_eighth = null;

    public function __construct()
    {
        $this->betQualificationCountries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstCountryRes(): ?CountriesTeams
    {
        return $this->first_country_res;
    }

    public function setFirstCountryRes(?CountriesTeams $first_country_res): self
    {
        $this->first_country_res = $first_country_res;

        return $this;
    }

    public function getSecondCountryRes(): ?CountriesTeams
    {
        return $this->second_country_res;
    }

    public function setSecondCountryRes(?CountriesTeams $second_country_res): self
    {
        $this->second_country_res = $second_country_res;

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

    public function getTypePhase(): ?string
    {
        return $this->type_phase;
    }

    public function setTypePhase(string $type_phase): self
    {
        $this->type_phase = $type_phase;

        return $this;
    }

    /**
     * @return Collection<int, BetQualificationCountries>
     */
    public function getBetQualificationCountries(): Collection
    {
        return $this->betQualificationCountries;
    }

    public function addBetQualificationCountry(BetQualificationCountries $betQualificationCountry): self
    {
        if (!$this->betQualificationCountries->contains($betQualificationCountry)) {
            $this->betQualificationCountries->add($betQualificationCountry);
            $betQualificationCountry->setQualificationCountries($this);
        }

        return $this;
    }

    public function removeBetQualificationCountry(BetQualificationCountries $betQualificationCountry): self
    {
        if ($this->betQualificationCountries->removeElement($betQualificationCountry)) {
            // set the owning side to null (unless already changed)
            if ($betQualificationCountry->getQualificationCountries() === $this) {
                $betQualificationCountry->setQualificationCountries(null);
            }
        }

        return $this;
    }

    public function getCountrie1Eighth(): ?CountriesTeams
    {
        return $this->countrie_1_eighth;
    }

    public function setCountrie1Eighth(?CountriesTeams $countrie_1_eighth): self
    {
        $this->countrie_1_eighth = $countrie_1_eighth;

        return $this;
    }

    public function getCountrie2Eighth(): ?CountriesTeams
    {
        return $this->countrie_2_eighth;
    }

    public function setCountrie2Eighth(?CountriesTeams $countrie_2_eighth): self
    {
        $this->countrie_2_eighth = $countrie_2_eighth;

        return $this;
    }

    public function getCountrie3Eighth(): ?CountriesTeams
    {
        return $this->countrie_3_eighth;
    }

    public function setCountrie3Eighth(?CountriesTeams $countrie_3_eighth): self
    {
        $this->countrie_3_eighth = $countrie_3_eighth;

        return $this;
    }

    public function getCountrie4Eighth(): ?CountriesTeams
    {
        return $this->countrie_4_eighth;
    }

    public function setCountrie4Eighth(?CountriesTeams $countrie_4_eighth): self
    {
        $this->countrie_4_eighth = $countrie_4_eighth;

        return $this;
    }
}
