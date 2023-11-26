<?php

namespace App\Api\Model;

use App\Entity\Employee;
use Symfony\Component\Validator\Constraints as Assert;

class EmployeeInput
{
    public function __construct(
        #[Assert\NotBlank]
        public string $firstName,
        #[Assert\NotBlank]
        public string $secondName,
    ) {}

    public function toEntity(Employee $employee = new Employee()): Employee
    {
        $employee->setFirstName($this->firstName);
        $employee->setSecondName($this->secondName);
        return $employee;
    }
}