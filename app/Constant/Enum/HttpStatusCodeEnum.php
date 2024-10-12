<?php

namespace App\Constant\Enum;

enum HttpStatusCodeEnum: int
{
    case NOT_FOUND = 400;
    case USER_NOT_IN_ORG = 400_001;
    case INVALID_VALUE = 400_002;
    case INVALID_STATUS = 400003;
    case CAN_NOT_DELETED = 400004;
    case CAN_NOT_UPDATE = 4000041;
    case INVALID_DATE = 400005;
    case NOT_NULL = 400007;
    case YOU_HAVE_NOT_PERMISSION = 401_001;
    case SERVER_ERROR = 500;
}
