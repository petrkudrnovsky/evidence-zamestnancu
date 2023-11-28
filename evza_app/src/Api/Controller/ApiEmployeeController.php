<?php

namespace App\Api\Controller;

use App\Api\Model\EmployeeInput;
use App\Api\Model\EmployeeOutput;
use App\Entity\Account;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use App\Service\Employee\EmployeeManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class ApiEmployeeController extends AbstractFOSRestController
{
    #[Rest\Get('/employees', name: 'api_employees_list')]
    #[Rest\View]
    public function list(EmployeeRepository $repository): array
    {
        $employeeModels = array_map(
            fn(Employee $employee) => EmployeeOutput::fromEntity($employee, $this->getAccountUrls($employee), $this->getPositionNames($employee), [
                'GET' => $this->generateUrl('api_employees_single', ['id' => $employee->getId()]),
                'PUT' => $this->generateUrl('api_employees_edit', ['id' => $employee->getId()]),
                'DELETE' => $this->generateUrl('api_employees_delete', ['id' => $employee->getId()])
            ]),
            $repository->findAll()
        );

        return [
            'entries' => $employeeModels,
            '_url' => [
                'POST' => $this->generateUrl('api_employees_create'),
            ]
        ];
    }

    private function getAccountUrls(Employee $employee): array
    {
        $accounts = [];
        foreach($employee->getAccounts() as $account) {
            $accounts[] = ['GET' =>$this->generateUrl('api_accounts_single', ['id' => $account->getId()])];
        }

        return $accounts;
    }

    private function getPositionNames(Employee $employee): array
    {
        $positions = [];
        foreach ($employee->getPositions() as $position) {
            $positions[] = $position->getName();
        }

        return $positions;
    }

    #[Rest\Get('/employees/search/{query}', name: 'api_employees_search')]
    #[Rest\View]
    public function search(string $query, EmployeeManager $employeeManager): array
    {
        $employees = $employeeManager->getEmployeesBySearchTerm($query);
        return array_map(fn(Employee $employee) => EmployeeOutput::fromEntity($employee, [], [], []), $employees);
    }

    #[Rest\Get('/employees/{id}', name: 'api_employees_single', requirements: ['id' => '\d+'])]
    #[Rest\View]
    public function single(int $id, EmployeeManager $employeeManager): array
    {
        $employee = $employeeManager->getEmployeeById($id);
        $employeeOutput = EmployeeOutput::fromEntity($employee, $this->getAccountUrls($employee), $this->getPositionNames($employee), [[
            'PUT' => $this->generateUrl('api_employees_edit', ['id' => $employee->getId()]),
            'DELETE' => $this->generateUrl('api_employees_delete', ['id' => $employee->getId()])
        ]]);

        return [
            'entries' => $employeeOutput,
            '_url' => [
                'POST' => $this->generateUrl('api_employees_create'),
            ]
        ];
    }

    #[Rest\Post('/employees', name: 'api_employees_create')]
    #[ParamConverter('employeeInput', converter: 'fos_rest.request_body')]
    #[Rest\View(statusCode: 201)]
    public function create(EmployeeInput $employeeInput, EmployeeManager $employeeManager): EmployeeOutput
    {
        $employee = new Employee();
        $employee = $employeeInput->toEntity($employee);
        $employeeManager->saveToDatabase($employee);

        return EmployeeOutput::fromEntity($employee, $this->getAccountUrls($employee), $this->getPositionNames($employee), [
            'POST' => $this->generateUrl('api_employees_create'),
            'PUT' => $this->generateUrl('api_employees_edit', ['id' => $employee->getId()]),
            'DELETE' => $this->generateUrl('api_employees_delete', ['id' => $employee->getId()]),
        ]);
    }

    #[Rest\Put('/employees/{id}', name: 'api_employees_edit')]
    #[ParamConverter('employeeInput', converter: 'fos_rest.request_body')]
    #[Rest\View(statusCode: 201)]
    public function edit(int $id, EmployeeInput $employeeInput, EmployeeManager $employeeManager): EmployeeOutput
    {
        $employee = $employeeManager->getEmployeeById($id);
        $employee = $employeeInput->toEntity($employee);
        $employeeManager->saveToDatabase($employee);

        return EmployeeOutput::fromEntity($employee, $this->getAccountUrls($employee), $this->getPositionNames($employee), [
            'POST' => $this->generateUrl('api_employees_create'),
            'PUT' => $this->generateUrl('api_employees_edit', ['id' => $employee->getId()]),
            'DELETE' => $this->generateUrl('api_employees_delete', ['id' => $employee->getId()]),
        ]);
    }

    #[Rest\Delete('/employees/{id}', name: 'api_employees_delete')]
    #[Rest\View(statusCode: 204)]
    public function delete(int $id, EmployeeManager $employeeManager): array
    {
        $employeeManager->deleteEmployee($id);
        return [];
    }
}