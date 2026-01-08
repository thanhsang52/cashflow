<?php
namespace Modules\Cashflow\Database\Seeders;
use Illuminate\Database\Seeder;
use DB;
class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cashflow_transaction_categories')->insert([
			[
				'name'	 => 'Transfer',
				'type'	 => 'other',
				'system' => 1,
				'color'  => '#1abc9c',
			],[
				'name'	 => 'Clearing debt',
				'type'	 => 'income',
				'system' => 1,
				'color' => '#2ecc71'
			],[
				'name'	 => 'Receivable',
				'type'	 => 'income',
				'system' => 1,
				'color' => '#4834d4'
			],[
				'name'	 => 'Other',
				'type'	 => 'income',
				'system' => 1,
				'color' => '#eb4d4b'
			],
		]);
    }
}
