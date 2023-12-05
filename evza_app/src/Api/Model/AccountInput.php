<?php

namespace App\Api\Model;

use App\Entity\Account;
use App\Entity\Employee;
use DateTime;
use JMS\Serializer\Annotation\Type;

class AccountInput
{
    public function __construct(
        public string $name,
        #[Type("DateTime<'Y-m-d H:i:s'>")]
        public ?DateTime $expiration,
        public int $employeeId
    ) {}

    public function toEntity(Employee $employee, ?Account $account = null): Account
    {
        if($account == null) {
            return new Account($this->name, $this->expiration, $employee);
        } else {
            $account->setName($this->name);
            $account->setExpiration($this->expiration);
            // employee cannot be altered
            return $account;
        }
    }
}