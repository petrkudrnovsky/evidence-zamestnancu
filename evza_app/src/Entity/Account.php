<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?DateTime $expiration = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    /**
     * @param string $name
     * @param DateTime|null $expiration
     * @param Employee $employee
     */
    public function __construct(string $name, ?DateTime $expiration, Employee $employee)
    {
        $this->name = $name;
        $this->expiration = $expiration;
        $this->employee = $employee;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return DateTime|null
     */
    public function getExpiration(): ?DateTime
    {
        return $this->expiration;
    }

    /**
     * @param DateTime|null $expiration
     */
    public function setExpiration(?DateTime $expiration): void
    {
        $this->expiration = $expiration;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }
}