<?php

namespace App\Api\Controller;

use App\Api\Model\AccountOutput;
use App\Service\Account\AccountManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class ApiAccountController extends AbstractFOSRestController
{
    #[Rest\Get('/accounts/{id}', name: 'api_accounts_single')]
    #[Rest\View]
    public function single(?int $id, AccountManager $accountManager): AccountOutput
    {
        $account = $accountManager->getAccountById($id);
        return AccountOutput::fromEntity($account, $this->generateUrl('api_employees_single', ['id' => $account->getEmployee()->getId()]));
    }
}