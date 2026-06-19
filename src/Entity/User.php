<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(
        message:"{{ label }} est {{ value }}",
        groups:['registration']
    )]
    // #[Assert\NotBlank(message:"aaa {{ value }}, {{ label }} aaa")]
    // ne jamais toucher l'ORM!!!!
    #[ORM\Column]
    #[Assert\Email(
        message: "{{ label }} n'est pas valide",
        groups: ['changePassword']
    )]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = []; // on peut avoir * roles

    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank(
        message:"PASSWORD: {{ label }} est {{ value }}",
        groups:['registration']
    )]
    #[ORM\Column]
    #[Assert\Regex(
        pattern: "#(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,}#",
        match: false,
        message: '{{ label }} doit contenir au moins 1 chiffres, 1 lettre minuscule, 1 majuscule',
        groups:['registration']
    )]
    private ?string $password = null;

    #[Assert\EqualTo(
        propertyPath: 'password',
        message: "{{ label }} doit etre egale a {{ compared_value }}",
        groups:['registration']
    )]
    private ?string $confirm_password = null;

    // CHANGE PASSWORD

    #[Assert\NotBlank(
        message:"OLD PASSWORD: {{ label }} est {{ value }}",
        groups:['changePassword']
    )]
    private ?string $oldPassword = null;

    #[Assert\NotBlank(
        message:"NEW PASSWORD: {{ label }} est {{ value }}",
        groups:['changePassword']
    )]
    private ?string $newPassword = null;

    #[Assert\EqualTo(
        propertyPath: 'newPassword',
        message: "{{ value }} doit etre egale a {{ compared_value }}",
        groups:['changePassword']
    )]
    #[Assert\NotBlank(
        message:"CONFIRM PASSWORD: {{ label }} est {{ value }}",
        groups:['changePassword']
    )]
    private ?string $confirmNewPassword = null;



    #[Assert\NotBlank(message:"NOM: {{ label }} est {{ value }}")]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[Assert\NotBlank(
        message:"PRENOM: {{ label }} est {{ value }}",
        groups:['registration']
    )]
    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: "Mettez un {{ label }} de plus de 2 caracteres",
        maxMessage: "Mettez un {{ label }} moins de 21 caracteres",
    )]
    private ?string $firstName = null;

    /**
     * @var Collection<int, Address>
     */
    #[ORM\OneToMany(targetEntity: Address::class, mappedBy: 'user')]
    private Collection $addresses;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user')]
    private Collection $orders;

    #[ORM\Column(options: ['default' => false])]
    private bool $isActive = false;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $activationToken = null;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    // CONFIRM PASSWORD

    public function getConfirmPassword(): ?string
    {
        return $this->confirm_password;
    }

    public function setConfirmPassword(string $confirm_password): static
    {
        $this->confirm_password = $confirm_password;

        return $this;
    }

    // OLD PASSWORD

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $password): static
    {
        $this->oldPassword = $password;

        return $this;
    }

    // NEW PASSWORD

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $password): static
    {
        $this->newPassword = $password;

        return $this;
    }

    // CONFIRM NEW PASSWORD

    public function getConfirmNewPassword(): ?string
    {
        return $this->confirmNewPassword;
    }

    public function setConfirmNewPassword(string $password): static
    {
        $this->confirmNewPassword = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddresses(Address $name): static
    {
        if (!$this->addresses->contains($name)) {
            $this->addresses->add($name);
            $name->setUser($this);
        }

        return $this;
    }

    public function removeAddresses(Address $name): static
    {
        if ($this->addresses->removeElement($name)) {
            // set the owning side to null (unless already changed)
            if ($name->getUser() === $this) {
                $name->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }


    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }

    public function setActivationToken(?string $activationToken): static
    {
        $this->activationToken = $activationToken;

        return $this;
    }
}
