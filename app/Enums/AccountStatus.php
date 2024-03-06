<?php

namespace App\Enums;

enum AccountStatus: int
{
    case Inactive = 0;
    case Active = 1;
    case Suspended = 2;
}
