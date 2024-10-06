<?php

namespace App\Http\Controllers;

use App\Models\CommonKeyCode;

class CommonKeyCodeController extends Controller
{
    protected function genNewKeyCode(string $type, string $lengthFormat, string $orgId): string
    {
        $currentIndex = $this->getCurrentIndexBy($type, $orgId);
        return sprintf($lengthFormat, $currentIndex);
    }

    protected function getCurrentIndexBy(string $type, string $orgId): int
    {
        $entity = CommonKeyCode::query()->where('org_id', $orgId)
            ->where('object_type', $type)
            ->first();

        return $this->getCurrentIndexByEntity($type, $orgId, $entity);
    }

    protected function getCurrentIndexByEntity(string $type, string $orgId, ?CommonKeyCode $entity)
    {
        if (is_null($entity)) {
            $entity = $this->create($type, $orgId);
        } else {
            $entity = $this->update($entity);
        }

        return $entity->current_index;
    }

    protected function create(string $type, string $orgId)
    {
        return CommonKeyCode::query()->create([
            'org_id' => $orgId,
            'object_type' => $type,
            'current_index' => 1,
        ]);
    }

    protected function update(CommonKeyCode $entity)
    {
        $entity->current_index++;
        $entity->save();

        return $entity;
    }
}
