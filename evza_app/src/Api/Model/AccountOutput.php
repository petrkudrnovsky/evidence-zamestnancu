<?php

namespace App\Api\Model;

use App\Entity\Account;
use DateTime;

class AccountOutput
{
    public function __construct(
        public int $id,
        public string $name,
        public ?DateTime $expiration,
        public bool $isPermanent,
        public string $employee,
    ) {}

    public static function fromEntity(Account $account, string $employeeUrl): self
    {
        return new self($account->getId(), $account->getName(), $account->getExpiration(), !((bool)$account->getExpiration()), $employeeUrl);
    }
}