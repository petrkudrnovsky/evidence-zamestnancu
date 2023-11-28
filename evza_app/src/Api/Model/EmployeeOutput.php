<?php

namespace App\Api\Model;

use App\Entity\Employee;

class EmployeeOutput
{
    public function __construct(
        public int $id,
        public string $firstName,
        public string $secondName,
        public ?string $phoneNumber,
        public ?string $email,
        public ?string $note,
        public bool $active,
        public array $accounts,
        public array $positions,
        public array $_url,
    ) {}

    public static function fromEntity(Employee $employee, array $accountUrls, array $positionNames, array $urls): self
    {
        return new self(
            $employee->getId(),
            $employee->getFirstName(),
            $employee->getSecondName(),
            $employee->getPhoneNumber(),
            $employee->getEmail(),
            $employee->getNote(),
            $employee->isActive(),
            $accountUrls,
            $positionNames,
            $urls
        );
    }
}