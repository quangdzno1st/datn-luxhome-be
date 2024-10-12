<?php

namespace App\Services;

interface CommonKeyCodeService
{
    public function genNewKeyCode(string $type, string $lengthFormat, string $orgId);
}