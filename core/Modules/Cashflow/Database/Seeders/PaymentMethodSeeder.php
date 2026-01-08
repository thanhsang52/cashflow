<?php
namespace Modules\Cashflow\Database\Seeders;
use Illuminate\Database\Seeder;
use DB;
class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cashflow_payment_methods')->insert([
			['name' => 'Cash'],
            ['name' => 'Bank Transfer'],
			['name' => 'Bank Cheque']
		]);
    }
}
