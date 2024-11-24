<?php

namespace App\Repositories\Wallet;

use App\Models\Wallet;
use App\Repositories\Base\BaseRepository;

class WalletRepository extends BaseRepository implements WalletInterface
{
    public function model(): string
    {
        return Wallet::class;
    }

    private function getAllByUserCondition()
    {

    }
}