<?php

namespace App\Controller;

use App\Form\AccountType;
use App\Form\Model\AccountTypeModel;
use App\Service\Account\AccountManager;
use App\Service\Employee\EmployeeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/employee/{employeeId}/account/new', name: 'app_account_new')]
    public function new(int $employeeId, Request $request, AccountManager $accountManager, EmployeeManager $employeeManager, FormFactoryInterface $formFactory): Response
    {
        $accountModel = new AccountTypeModel(null, null, null);

        $form = $this->createForm(AccountType::class, $accountModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filledAccountModel = $form->getData();
            $employee = $employeeManager->getEmployeeById($employeeId);
            $accountManager->saveModelToDatabase($filledAccountModel, $employee, null);

            return $this->redirectToRoute('app_employee_detail_accounts', ['id' => $employeeId]);
        }

        return $this->render('pages/account/account-form.html.twig', [
            'form' => $form,
            'heading' => 'Vytvořit nový účet'
        ]);
    }

    #[Route('/employee/{employeeId}/account/{accountId}/edit', name: 'app_account_edit')]
    public function edit(int $employeeId, int $accountId, Request $request, AccountManager $accountManager, EmployeeManager $employeeManager): Response
    {
        $accountModel = $accountManager->getModelById($accountId);
        $form = $this->createForm(AccountType::class, $accountModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filledAccountModel = $form->getData();
            $employee = $employeeManager->getEmployeeById($employeeId);
            $accountManager->saveModelToDatabase($filledAccountModel, $employee, $accountId);
            return $this->redirectToRoute('app_employee_detail_accounts', ['id' => $employeeId]);
        }
        return $this->render('pages/account/account-form.html.twig', [
            'form' => $form,
            'heading' => 'Editovat účet'
        ]);
    }

    #[Route('/employee/{employeeId}/account/{accountId}/delete', name: 'app_account_delete')]
    public function delete(int $accountId, int $employeeId, AccountManager $accountManager): Response
    {
        $accountManager->deleteAccount($accountId);
        return $this->redirectToRoute('app_employee_detail_accounts', ['id' => $employeeId]);
    }
}
