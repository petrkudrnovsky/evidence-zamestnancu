<?php

namespace App\Form\Model;

use App\Entity\Employee;
use Symfony\Component\Validator\Constraints as Assert;

class EmployeeTypeModel
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $firstName,
        #[Assert\NotBlank]
        public ?string $secondName,
        public ?string $phoneNumber,
        public ?string $email,
        public ?string $note,
        public ?array $positions,
        public ?bool $active,
        public ?string $profilePhotoFilename,
    )
    {}

    public function toEntity(): Employee
    {
        $employee = new Employee();
        $employee->setFirstName($this->firstName);
        $employee->setSecondName($this->secondName);
        $employee->setPhoneNumber($this->phoneNumber);
        $employee->setEmail($this->email);
        $employee->setNote($this->note);
        $employee->setActive($this->active);

        foreach($this->positions as $position) {
            $employee->addPosition($position);
        }

        return $employee;

    }
}