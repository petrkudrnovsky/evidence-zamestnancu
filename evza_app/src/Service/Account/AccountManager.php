<?php

namespace App\Service\Account;

use App\Entity\Account;
use App\Form\Model\AccountTypeModel;
use App\Repository\AccountRepository;
use App\Service\Employee\EmployeeManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountManager
{
    public function __construct(
        public EntityManagerInterface $em,
        public AccountRepository $accountRepository,
        public EmployeeManager $employeeManager,
    ) {}

    public function getAccountById($accountId): Account
    {
        $account = $this->accountRepository->find($accountId);
        if (!$account) {
            throw new NotFoundHttpException("Account with ID $accountId not found.");
        }

        return $account;
    }

    public function saveModelToDatabase(AccountTypeModel $model, int $employeeId, ?int $accountId): Account
    {
        if($accountId) {
            $account = $this->getAccountById($accountId);
            $account->setName($model->name);
            $account->setExpiration($model->expiration);
        }
        else {
            $employee = $this->employeeManager->getEmployeeById($employeeId);
            $account = $model->toEntity($employee);
        }

        $this->em->persist($account);
        $this->em->flush();

        return $account;
    }

    public function getModelById(int $accountId): AccountTypeModel
    {
        $account = $this->accountRepository->find($accountId);
        if (!$account) {
            throw new NotFoundHttpException("Account with ID $accountId not found.");
        }

        return AccountTypeModel::fromEntity($account);
    }

    /*public function getModelFromDatabase(): AccountTypeModel
    {
        $account = $this->repository->fin
    }*/
}