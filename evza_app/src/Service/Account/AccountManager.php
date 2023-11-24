<?php

namespace App\Service\Account;

use App\Entity\Account;
use App\Entity\Employee;
use App\Form\Model\AccountTypeModel;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountManager
{
    public function __construct(
        public EntityManagerInterface $em,
        public AccountRepository      $accountRepository,
    )
    {
    }

    public function getAccountById($accountId): Account
    {
        $account = $this->accountRepository->find($accountId);
        if (!$account) {
            throw new NotFoundHttpException("Account with ID $accountId not found.");
        }

        return $account;
    }

    public function saveModelToDatabase(AccountTypeModel $model, Employee $employee, ?int $accountId): Account
    {
        if ($accountId) {
            $account = $this->getAccountById($accountId);
            $account->setName($model->name);
            if($model->isPermanent) {
                $account->setExpiration(null);
            }
            else {
                $account->setExpiration($model->expiration);
            }
        } else {
            $account = $model->toEntity($employee);
        }

        $this->em->persist($account);
        $this->em->flush();

        return $account;
    }

    public function getModelById(int $accountId): AccountTypeModel
    {
        $account = $this->getAccountById($accountId);
        return AccountTypeModel::fromEntity($account);
    }

    public function deleteAccount(int $accountId): void
    {
        $account = $this->getAccountById($accountId);
        $this->em->remove($account);
        $this->em->flush();
    }
}
