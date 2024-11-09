<?php

namespace App\Constant\Enum;

enum TypeCodeEnum:string
{
    case STRING_6_CHAR = "%06d";

    case ROOM_TYPE = "LHR-";

    case ORDER_TYPE = "LHO-";
}
