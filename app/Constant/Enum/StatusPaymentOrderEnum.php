<?php

namespace App\Constant\Enum;

enum StatusPaymentOrderEnum: int
{
    case CHUA_THANH_TOAN = 1;
    case  DA_THANH_TOAN = 2;
    case DA_HOAN_TIEN = 3;
    case CHUA_HOAN_TIEN = 4;
}
