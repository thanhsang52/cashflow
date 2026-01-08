<?php
namespace Modules\Cashflow\Database\Seeders;
use Illuminate\Database\Seeder;
use DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cashflow_currency')->insert([
			[
				'name' 			=> 'VND',
				'base_currency' => 1,
				'exchange_rate' => 1.00,
				'status' 		=> 1,
			],[
				'name' 			=> 'USD',
				'base_currency' => 0,
				'exchange_rate' => 24500,
				'status' 		=> 1,
			]
		]);
		
		//Default Settings
		DB::table('cashflow_settings')->insert([
			[
			  'name' => 'mail_type',
			  'value' => 'mail'
			],
			[
			  'name' => 'backend_direction',
			  'value' => 'ltr'
			],
			[
			  'name' => 'currency',
			  'value' => 'VND'
			],	
		]);
    }
}
