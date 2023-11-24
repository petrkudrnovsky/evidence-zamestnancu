<?php

namespace App\Service\Employee;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use App\Service\Account\AccountManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeeManager
{
    public function __construct(
        public EntityManagerInterface $em,
        public EmployeeRepository $employeeRepository,
        public AccountManager $accountManager,
    ) {}

    public function getEmployeeById($employeeId): Employee
    {
        $employee = $this->employeeRepository->find($employeeId);
        if (!$employee) {
            throw new NotFoundHttpException("Employee with ID $employeeId not found.");
        }

        return $employee;
    }
}