<?php
namespace Modules\Cashflow\Database\Seeders;
use Illuminate\Database\Seeder;
use DB;

class ContractSeeder​ extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // factory(App\Contract::class, 500)->create()->each(function($contract){
        //     $contract->save();
        // });
        DB::table('cashflow_contracts')->insert([
			[
				'name'	     => 'Contract 1',
				//'number'	 => 'hd-001',
                'code'       => 'HD001',
				'vendor_id'  => 1,
                'type'       => 'main',
                'effect_from'=> '2023-03-01',
                'effect_to'  => '2024-03-01',
                'base_value' => '100900000',
                'included_vat'=> 0,
                'reference'   => '',
                'note'        => '',
                'created_user_id'=>1
			],[
				'name'	     => 'Contract 2',
				//'number'	 => 'hd-002',
                'code'       => 'HD002',
				'vendor_id'  => 2,
                'type'       => 'main',
                'effect_from'=> '2023-03-01',
                'effect_to'  => '2024-03-01',
                'base_value' => '150900000',
                'included_vat'=> 1,
                'reference'   => '',
                'note'        => '',
                'created_user_id'=>1
			]
		]);
    }
}
