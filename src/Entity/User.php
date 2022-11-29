<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cette adresse email')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: BetUser::class, orphanRemoval: true)]
    private Collection $betUsers;

    #[ORM\Column]
    private ?bool $valide_register = null;

    #[ORM\Column]
    private ?bool $paid = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $url_picture = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    private ?UserGroups $groupes = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserScores $userScores = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $modified_at = null;

    #[ORM\Column]
    private ?bool $reminder_bet_email = null;

    #[ORM\Column]
    private ?bool $status_score_email = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: BetQualificationCountries::class, orphanRemoval: true)]
    private Collection $betQualificationCountries;

    public function __construct()
    {
        $this->betUsers = new ArrayCollection();
        $this->betQualificationCountries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $betUser->setUser($this);
        }

        return $this;
    }

    public function removeBetUser(BetUser $betUser): self
    {
        if ($this->betUsers->removeElement($betUser)) {
            // set the owning side to null (unless already changed)
            if ($betUser->getUser() === $this) {
                $betUser->setUser(null);
            }
        }

        return $this;
    }

    public function isValideRegister(): ?bool
    {
        return $this->valide_register;
    }

    public function setValideRegister(bool $valide_register): self
    {
        $this->valide_register = $valide_register;

        return $this;
    }

    public function isPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getUrlPicture(): ?string
    {
        return $this->url_picture;
    }

    public function setUrlPicture(?string $url_picture): self
    {
        $this->url_picture = $url_picture;

        return $this;
    }

    public function getGroupes(): ?UserGroups
    {
        return $this->groupes;
    }

    public function setGroupes(?UserGroups $groupes): self
    {
        $this->groupes = $groupes;

        return $this;
    }

    public function getUserScores(): ?UserScores
    {
        return $this->userScores;
    }

    public function setUserScores(UserScores $userScores): self
    {
        // set the owning side of the relation if necessary
        if ($userScores->getUser() !== $this) {
            $userScores->setUser($this);
        }

        $this->userScores = $userScores;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modified_at;
    }

    public function setModifiedAt(\DateTimeInterface $modified_at): self
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    public function isReminderBetEmail(): ?bool
    {
        return $this->reminder_bet_email;
    }

    public function setReminderBetEmail(bool $reminder_bet_email): self
    {
        $this->reminder_bet_email = $reminder_bet_email;

        return $this;
    }

    public function isStatusScoreEmail(): ?bool
    {
        return $this->status_score_email;
    }

    public function setStatusScoreEmail(bool $status_score_email): self
    {
        $this->status_score_email = $status_score_email;

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
            $betQualificationCountry->setUser($this);
        }

        return $this;
    }

    public function removeBetQualificationCountry(BetQualificationCountries $betQualificationCountry): self
    {
        if ($this->betQualificationCountries->removeElement($betQualificationCountry)) {
            // set the owning side to null (unless already changed)
            if ($betQualificationCountry->getUser() === $this) {
                $betQualificationCountry->setUser(null);
            }
        }

        return $this;
    }
}
