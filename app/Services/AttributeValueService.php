<?php

namespace App\Services;

interface AttributeValueService
{
    public function getAttributeFetchJoinValueBy(array $codes, $orgId);
}