<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('admins')->insert([
//           [
//               'code'=>'djfhjds',
//               'name'=>'Nam Admin',
//               'username'=>'namadmin',
//               'email'=>'quachnam3010@gmail.com',
//               'password'=>Hash::make('11111111'),
//           ]
//        ]);
        $this->call([
//            OrderSeeder::class,
//            ServiceSeeder::class,
        VoucherSeeder::class,
        ]);
    }
}
