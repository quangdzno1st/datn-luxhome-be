<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Base\BaseRepository;

class UserRepository extends BaseRepository implements UserInterface
{

    public function model(): string
    {
        return User::class;
    }

    public function getAll($request)
    {
        $user = auth()->user();
        $query = $this->model::query();
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($user->type != User::ADMIN) {
            $query->where(function ($q) use ($user) {
                $q->where('id', $user->id);
                if ($user->type == User::HOTELIER) {
                    $q->orWhere('org_id', $user->org_id)->where('type',"!=",User::ADMIN);
                }
                $q->orWhere('type', User::CUSTOMER);
            });
        }

        if ($request->has('type') && !empty($request->input('type'))) {
            $query->where('type', $request->input('type'));
        }

        return $query->paginate(10);
    }

    public function getByRankAndTotalAmountOrdered($request)
    {
        $query = $this->model::query()->select('id', 'email', 'name');

        if ($request->rank > 0) {
            $query->where('rank', $request->rank);
        }
        if (!is_null($request->total_amount_ordered_from)) {
            $query->where('total_amount_ordered', '>=', $request->total_amount_ordered_from);
        }
        if (!is_null($request->total_amount_ordered_to)) {
            $query->where('total_amount_ordered', '<=', $request->total_amount_ordered_to);
        }

        return $query->get();
    }

}