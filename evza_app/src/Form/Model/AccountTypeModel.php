<?php

namespace App\Form\Model;

use App\Entity\Employee;
use DateTime;

class AccountTypeModel
{
    public string $name;
    public ?DateTime $expiration;
}