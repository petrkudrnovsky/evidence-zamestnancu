<?php

namespace App\Api\Model;

use App\Entity\Employee;

class EmployeeOutput
{
    public function __construct(
        public ?string $firstName,
        public ?string $secondName,
    ) {}

    public static function fromEntity(Employee $employee, array $urls): self
    {
        return new self(
            $employee->getFirstName(),
            $employee->getSecondName(),
            $urls
        );
    }
}