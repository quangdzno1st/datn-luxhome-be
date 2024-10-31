<?php

namespace App\Services\impl;

use App\Repositories\AttributeValue\AttributeValueValueRepository;
use App\Services\AttributeValueService;

class AttributeValueServiceImpl implements AttributeValueService
{
    private AttributeValueValueRepository $attributeValueValueRepos;

    public function __construct(AttributeValueValueRepository $attributeValueValueRepos)
    {
        $this->attributeValueValueRepos = $attributeValueValueRepos;
    }


    public function getAttributeFetchJoinValueBy(array $codes, $orgId)
    {
        return $this->attributeValueValueRepos->getByCodeInAndOrgId($codes, $orgId);
    }

}