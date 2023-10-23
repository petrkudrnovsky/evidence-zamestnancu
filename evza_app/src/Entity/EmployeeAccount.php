<?php

namespace App\Entity;

use App\Repository\EmployeeAccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeAccountRepository::class)]
class EmployeeAccount
{
    public int $id;
    private int $name;
    private ?\DateTime $expiration;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    /**
     * @param int $name
     * @param \DateTime|null $expiration
     * @param Employee|null $employee
     */
    public function __construct(int $name, ?\DateTime $expiration, ?Employee $employee)
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
     * @return int
     */
    public function getName(): int
    {
        return $this->name;
    }

    /**
     * @param int $name
     */
    public function setName(int $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiration(): ?\DateTime
    {
        return $this->expiration;
    }

    /**
     * @param \DateTime|null $expiration
     */
    public function setExpiration(?\DateTime $expiration): void
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