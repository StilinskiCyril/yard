<?php

namespace App\Enums;

enum PaymentChannel: int
{
    case Mpesa = 1;
    case Card = 2;
    case Paypal = 3;
    case Offline = 4;
}
