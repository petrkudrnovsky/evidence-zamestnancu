<?php

namespace App\Api\Controller;

use App\Api\Model\AccountInput;
use App\Api\Model\AccountOutput;
use App\Service\Account\AccountManager;
use App\Service\Employee\EmployeeManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ApiAccountController extends AbstractFOSRestController
{
    #[Rest\Get('/accounts/{id}', name: 'api_accounts_single')]
    #[Rest\View(statusCode: 200)]
    public function single(?int $id, AccountManager $accountManager): AccountOutput
    {
        $account = $accountManager->getAccountById($id);
        return AccountOutput::fromEntity($account, $this->generateUrl('api_employees_single', ['id' => $account->getEmployee()->getId()]));
    }

    #[Rest\Post('/accounts', name: 'api_accounts_create')]
    #[ParamConverter('accountInput', converter: 'fos_rest.request_body')]
    #[Rest\View(statusCode: 201)]
    public function create(AccountInput $accountInput, AccountManager $accountManager, EmployeeManager $employeeManager): AccountOutput
    {
        $employee = $employeeManager->getEmployeeById($accountInput->employeeId);
        $account = $accountInput->toEntity($employee);
        $accountManager->saveToDatabase($account);

        return AccountOutput::fromEntity($account, $this->generateUrl('api_employees_single', ['id' => $account->getEmployee()->getId()]));
    }

    #[Rest\Delete('accounts/{id}', name: 'api_accounts_delete')]
    #[Rest\View(statusCode: 204)]
    public function delete(int $id, AccountManager $accountManager): array
    {
        $accountManager->deleteAccount($id);
        return [];
    }
}