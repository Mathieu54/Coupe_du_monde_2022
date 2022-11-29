<?php

namespace App\Entity;

use App\Repository\CountriesTeamsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountriesTeamsRepository::class)]
class CountriesTeams
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $iso_flag = null;

    #[ORM\Column(length: 3)]
    private ?string $categories = null;

    #[ORM\OneToMany(mappedBy: 'countrie_1', targetEntity: Matches::class)]
    private Collection $matches;

    #[ORM\OneToMany(mappedBy: 'countrie_1', targetEntity: BetQualificationCountries::class, orphanRemoval: true)]
    private Collection $betQualificationCountries;

    #[ORM\OneToMany(mappedBy: 'countrie_1_eighth', targetEntity: QualificationCountries::class)]
    private Collection $qualificationCountriesRes;

    #[ORM\OneToMany(mappedBy: 'countrie_2_eighth', targetEntity: QualificationCountries::class)]
    private Collection $qualificationCountriesRes2;

    #[ORM\OneToMany(mappedBy: 'countrie_3_eighth', targetEntity: QualificationCountries::class)]
    private Collection $qualificationCountriesRes3;

    #[ORM\OneToMany(mappedBy: 'countrie_4_eighth', targetEntity: QualificationCountries::class)]
    private Collection $qualificationCountriesRes4;

    public function __construct()
    {
        $this->matches = new ArrayCollection();
        $this->betQualificationCountries = new ArrayCollection();
        $this->qualificationCountriesRes = new ArrayCollection();
        $this->qualificationCountriesRes2 = new ArrayCollection();
        $this->qualificationCountriesRes3 = new ArrayCollection();
        $this->qualificationCountriesRes4 = new ArrayCollection();
    }

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

    public function getIsoFlag(): ?string
    {
        return $this->iso_flag;
    }

    public function setIsoFlag(string $iso_flag): self
    {
        $this->iso_flag = $iso_flag;

        return $this;
    }

    public function getCategories(): ?string
    {
        return $this->categories;
    }

    public function setCategories(string $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection<int, Matches>
     */
    public function getMatches(): Collection
    {
        return $this->matches;
    }

    public function addMatch(Matches $match): self
    {
        if (!$this->matches->contains($match)) {
            $this->matches->add($match);
            $match->setCountrie1($this);
        }

        return $this;
    }

    public function removeMatch(Matches $match): self
    {
        if ($this->matches->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getCountrie1() === $this) {
                $match->setCountrie1(null);
            }
        }

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
            $betQualificationCountry->setCountrie1($this);
        }

        return $this;
    }

    public function removeBetQualificationCountry(BetQualificationCountries $betQualificationCountry): self
    {
        if ($this->betQualificationCountries->removeElement($betQualificationCountry)) {
            // set the owning side to null (unless already changed)
            if ($betQualificationCountry->getCountrie1() === $this) {
                $betQualificationCountry->setCountrie1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QualificationCountries>
     */
    public function getQualificationCountriesRes(): Collection
    {
        return $this->qualificationCountriesRes;
    }

    public function addQualificationCountriesRe(QualificationCountries $qualificationCountriesRe): self
    {
        if (!$this->qualificationCountriesRes->contains($qualificationCountriesRe)) {
            $this->qualificationCountriesRes->add($qualificationCountriesRe);
            $qualificationCountriesRe->setCountrie1Eighth($this);
        }

        return $this;
    }

    public function removeQualificationCountriesRe(QualificationCountries $qualificationCountriesRe): self
    {
        if ($this->qualificationCountriesRes->removeElement($qualificationCountriesRe)) {
            // set the owning side to null (unless already changed)
            if ($qualificationCountriesRe->getCountrie1Eighth() === $this) {
                $qualificationCountriesRe->setCountrie1Eighth(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QualificationCountries>
     */
    public function getQualificationCountriesRes2(): Collection
    {
        return $this->qualificationCountriesRes2;
    }

    public function addQualificationCountriesRes2(QualificationCountries $qualificationCountriesRes2): self
    {
        if (!$this->qualificationCountriesRes2->contains($qualificationCountriesRes2)) {
            $this->qualificationCountriesRes2->add($qualificationCountriesRes2);
            $qualificationCountriesRes2->setCountrie2Eighth($this);
        }

        return $this;
    }

    public function removeQualificationCountriesRes2(QualificationCountries $qualificationCountriesRes2): self
    {
        if ($this->qualificationCountriesRes2->removeElement($qualificationCountriesRes2)) {
            // set the owning side to null (unless already changed)
            if ($qualificationCountriesRes2->getCountrie2Eighth() === $this) {
                $qualificationCountriesRes2->setCountrie2Eighth(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QualificationCountries>
     */
    public function getQualificationCountriesRes3(): Collection
    {
        return $this->qualificationCountriesRes3;
    }

    public function addQualificationCountriesRes3(QualificationCountries $qualificationCountriesRes3): self
    {
        if (!$this->qualificationCountriesRes3->contains($qualificationCountriesRes3)) {
            $this->qualificationCountriesRes3->add($qualificationCountriesRes3);
            $qualificationCountriesRes3->setCountrie3Eighth($this);
        }

        return $this;
    }

    public function removeQualificationCountriesRes3(QualificationCountries $qualificationCountriesRes3): self
    {
        if ($this->qualificationCountriesRes3->removeElement($qualificationCountriesRes3)) {
            // set the owning side to null (unless already changed)
            if ($qualificationCountriesRes3->getCountrie3Eighth() === $this) {
                $qualificationCountriesRes3->setCountrie3Eighth(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QualificationCountries>
     */
    public function getQualificationCountriesRes4(): Collection
    {
        return $this->qualificationCountriesRes4;
    }

    public function addQualificationCountriesRes4(QualificationCountries $qualificationCountriesRes4): self
    {
        if (!$this->qualificationCountriesRes4->contains($qualificationCountriesRes4)) {
            $this->qualificationCountriesRes4->add($qualificationCountriesRes4);
            $qualificationCountriesRes4->setCountrie4Eighth($this);
        }

        return $this;
    }

    public function removeQualificationCountriesRes4(QualificationCountries $qualificationCountriesRes4): self
    {
        if ($this->qualificationCountriesRes4->removeElement($qualificationCountriesRes4)) {
            // set the owning side to null (unless already changed)
            if ($qualificationCountriesRes4->getCountrie4Eighth() === $this) {
                $qualificationCountriesRes4->setCountrie4Eighth(null);
            }
        }

        return $this;
    }
}
