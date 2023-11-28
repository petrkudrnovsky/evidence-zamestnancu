<?php

namespace App\Api\Model;

use App\Entity\Account;

class AccountOutput
{
    public function __construct(
        public string $name,
        public ?\DateTime $expiration,
        public string $employee,
    ) {}

    public static function fromEntity(Account $account, string $employeeUrl): self
    {
        return new self($account->getName(), $account->getExpiration(), $employeeUrl);
    }
}