<?php

namespace App\Enums;

enum CheckoutStepEnum: int
{
    case FirstStep = 1;
    case SecondStep = 2;
    case ThirdStep = 3;
    case FourthStep = 4;
    case FifthStep = 5;
}
