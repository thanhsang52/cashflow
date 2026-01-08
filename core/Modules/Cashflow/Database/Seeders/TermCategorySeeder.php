<?php
namespace Modules\Cashflow\Database\Seeders;
use Illuminate\Database\Seeder;
use DB;
class TermCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cashflow_term_categories')->insert([
			[
				'name'	 => 'CDC fee',
				'data'   => '{}',
			],[
				'name'	 => 'Incentive',
				'data'   => '{}',
			],[
				'name'	 => 'Support',
				'data'   => '{}',
			],[
				'name'	 => 'Others',
				'data'   => '{}',
			],
		]);
    }
}
