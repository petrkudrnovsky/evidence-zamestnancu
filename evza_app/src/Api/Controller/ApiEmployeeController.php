<?php

namespace App\Api\Controller;

use App\Api\Model\EmployeeInput;
use App\Api\Model\EmployeeOutput;
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
            fn(Employee $employee) => EmployeeOutput::fromEntity($employee, [

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

    #[Rest\Post('/employees', name: 'api_employees_create')]
    #[ParamConverter('employeeInput', converter: 'fos_rest.request_body')]
    #[Rest\View(statusCode: 201)]
    public function create(Request $request, EmployeeInput $employeeInput, EmployeeManager $employeeManager): EmployeeOutput
    {
        $employee = new Employee();
        $employee = $employeeInput->toEntity($employee);
        $employeeManager->saveToDatabase($employee);

        return EmployeeOutput::fromEntity($employee, [
            'POST' => $this->generateUrl('api_employees_create'),
            'PUT' => $this->generateUrl('api_employees_edit', ['id' => $employee->getId()]),
            'DELETE' => $this->generateUrl('api_employees_delete', ['id' => $employee->getId()]),
        ]);
    }

    #[Rest\Put('/employees', name: 'api_employees_edit')]
    public function edit(): array
    {
        // to-do
    }

    #[Rest\Delete('/employees', name: 'api_employees_delete')]
    public function delete(): array
    {
        // to-do
    }
}