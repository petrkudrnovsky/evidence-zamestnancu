<?php

namespace App\Controller;

use App\Form\AccountType;
use App\Form\Model\AccountTypeModel;
use App\Service\Account\AccountManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/employee/{employeeId}/account/new', name: 'app_account_new')]
    public function new(int $employeeId, Request $request, AccountManager $accountManager, FormFactoryInterface $formFactory): Response
    {
        $accountModel = new AccountTypeModel(null, null, null);

        $form = $formFactory->createNamed('Vytvořit nový účet', AccountType::class, $accountModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filledAccountModel = $form->getData();
            $accountManager->saveModelToDatabase($filledAccountModel, $employeeId, null);

            return $this->redirectToRoute('app_employee_detail_accounts', ['id' => $employeeId]);
        }

        return $this->render('pages/account/create-account.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/employee/{employeeId}/account/{accountId}/edit', name: 'app_account_edit')]
    public function edit(int $employeeId, int $accountId, Request $request, AccountManager $accountManager): Response
    {
        $accountModel = $accountManager->getModelById($accountId);

        $form = $this->createForm(AccountType::class, $accountModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filledAccountModel = $form->getData();
            $accountManager->saveModelToDatabase($filledAccountModel, $employeeId, $accountId);

            return $this->redirectToRoute('app_employee_detail_accounts', ['id' => $employeeId]);
        }

        return $this->render('pages/account/create-account.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/employee/{employeeId}/account/{accountId}/delete', name: 'app_account_delete')]
    public function delete(): Response
    {
        // vytahnu si account podle idcka - premapuju do AccountType
        // zkontroluju, jestli jsem na permanentnim uctu (pozdeji) + jedna se o docasny ucet - jen ten mohu editovat
        // vytvorim form
        // handleSubmit a zase poslu do servisy, ktera mi to updatne
        // redirect na seznam uctu uzivatele

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
}
