<?php

namespace App\Repositories\User;

use App\Models\Room;
use App\Models\User;
use App\Repositories\Base\BaseRepository;

class UserRepository extends BaseRepository implements UserInterface
{

    public function model(): string
    {
        return User::class;
    }

    public function getAll($request): ?User
    {
        $query = $this->model::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        return $query->paginate(15);
    }


}