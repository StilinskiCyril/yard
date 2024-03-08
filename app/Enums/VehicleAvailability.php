<?php

namespace App\Enums;

enum VehicleAvailability: int
{
    case Available = 1;
    case Sold = 2;
    case InTransit = 3;
}
