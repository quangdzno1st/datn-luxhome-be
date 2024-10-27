<?php

namespace App\Constant\Enum;

enum HttpStatusCodeEnum: int
{
    case CAN_NOT_DELETED = 402;
    case USER_NOT_IN_ORG = 401;
    case INVALID_VALUE = 400;
    case NOT_FOUND = 404;
    case CAN_NOT_UPDATE = 405;
    case INVALID_DATE = 406;
    case NOT_NULL = 408;
    case YOU_HAVE_NOT_PERMISSION = 403;
    case SERVER_ERROR = 500;
}
