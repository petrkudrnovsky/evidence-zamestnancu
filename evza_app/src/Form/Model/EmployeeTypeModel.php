<?php

namespace App\Form\Model;

use App\Entity\Employee;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class EmployeeTypeModel
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $firstName,
        #[Assert\NotBlank]
        public ?string $secondName,
        public ?string $phoneNumber,
        #[Assert\Email]
        public ?string $email,
        public ?string $note,
        public Collection $positions,
        public ?bool $active,
        public ?string $profilePhotoFilename,
    )
    {}

    public function toEntity(?Employee $employee): Employee
    {
        if(!$employee) {
            $employee = new Employee();
        }
        $employee->setFirstName($this->firstName);
        $employee->setSecondName($this->secondName);
        $employee->setPhoneNumber($this->phoneNumber);
        $employee->setEmail($this->email);
        $employee->setNote($this->note);
        $employee->setActive($this->active);
        $employee->setProfilePhotoFilename($this->profilePhotoFilename);

        foreach($this->positions as $position) {
            $employee->addPosition($position);
        }

        return $employee;
    }

    public static function fromEntity(Employee $employee): self
    {
        return new self(
            $employee->getFirstName(),
            $employee->getSecondName(),
            $employee->getPhoneNumber(),
            $employee->getEmail(),
            $employee->getNote(),
            $employee->getPositions(),
            $employee->isActive(),
            $employee->getProfilePhotoFilename()
        );
    }
}