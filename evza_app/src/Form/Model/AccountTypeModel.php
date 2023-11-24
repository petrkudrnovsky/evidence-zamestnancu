<?php

namespace App\Form\Model;

use App\Entity\Account;
use App\Entity\Employee;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class AccountTypeModel
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 250, minMessage: 'Jméno účtu musí mít min. 3 znaky', maxMessage: 'Jméno účtu musí mít méně než 250 znaků.')]
        public ?string $name,
        #[Assert\When(
            expression: 'this.isPermanent == false',
            constraints: [
                new Assert\NotBlank(message: 'Datum expirace nesmí být prázdné pro dočasný účet'),
                new Assert\GreaterThan('today', message: 'Datum expirace musí být v budoucnosti'),
            ],
        )]
        public ?DateTime $expiration,
        public ?bool $isPermanent
    ) {}

    public function toEntity(Employee $employee): Account
    {
        return new Account($this->name, $this->isPermanent ? null : $this->expiration, $employee);
    }

    public static function fromEntity(Account $account): self
    {
        return new self($account->getName(), $account->getExpiration(), $account->getExpiration() == null);
    }
}