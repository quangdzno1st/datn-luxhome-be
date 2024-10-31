<?php

namespace App\Constant\Enum;

enum RoleEnum: int
{
    case Admin = 3;
    case SupperAdmin = 2;
    case User = 1;
}