<?php

namespace App\Repositories\AttributeValue;

use App\Models\AttributeValue;
use App\Repositories\Base\BaseRepository;

class AttributeValueValueRepository extends BaseRepository implements AttributeValueInterface
{

    public function model(): string
    {
        return AttributeValue::class;
    }

    public function getByCodeInAndOrgId(array $codes, $orgId)
    {
        return AttributeValue::query()
            ->join('attributes as a', 'a.id', '=', 'attribute_values.attribute_id')
            ->where('a.org_id', $orgId)
            ->when($codes, function ($query, $codes) {
                return $query->whereIn('a.code', $codes);
            })->select('attribute_values.id', 'attribute_values.value_text')
            ->get();

    }
}