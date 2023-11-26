<?php

namespace App\Service\Employee;

use App\Entity\Employee;
use App\Form\Model\EmployeeTypeModel;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeeManager
{
    public function __construct(
        public EntityManagerInterface $em,
        public EmployeeRepository $employeeRepository,
    ) {}

    public function getEmployeeById($employeeId): Employee
    {
        $employee = $this->employeeRepository->find($employeeId);
        if (!$employee) {
            throw new NotFoundHttpException("Employee with ID $employeeId not found.");
        }

        return $employee;
    }

    public function saveModelToDatabase(EmployeeTypeModel $model, ?int $employeeId): Employee
    {
        if($employeeId) {
            $employee = $model->toEntity($this->getEmployeeById($employeeId));
        }
        else {
            $employee = $model->toEntity(null);
        }

        return $this->saveToDatabase($employee);
    }

    public function saveToDatabase(Employee $employee): Employee
    {
        $this->em->persist($employee);
        $this->em->flush();
        return $employee;
    }

    public function getModelById(int $employeeId): EmployeeTypeModel
    {
        $employee = $this->getEmployeeById($employeeId);
        return EmployeeTypeModel::fromEntity($employee);
    }

    public function deleteEmployee(int $employeeId): void
    {
        $employee = $this->getEmployeeById($employeeId);
        $this->em->remove($employee);
        $this->em->flush();
    }
}
