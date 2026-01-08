<?php

namespace Modules\Cashflow\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
			[
				'name'	        => 'Linh Ngô',
				'email'         => 'linhnt@medicare.com.vn',
                'permissions_id'       => 2,
                'status'        => 1,
                'email_verified_at'=> '2024-04-03 10:13:38.000',
                'password'      => Hash::make('123456789'),
            ],[
				'name'	        => 'Thu Lan',
				'email'         => 'lannt@medicare.vn',
                'permissions_id'       => 1,
                'status'        => 1,
                'email_verified_at'=> '2024-04-03 10:13:38.000',
                'password'      => Hash::make('123456789'),
            ],[
				'name'	        => 'Hồng',
				'email'         => 'hongvn@medicare.vn',
                'permissions_id'       => 2,
                'status'        => 1,
                'email_verified_at'=> '2024-04-03 10:13:38.000',
                'password'      => Hash::make('123456789'),
            ]
		]);
    }
}
