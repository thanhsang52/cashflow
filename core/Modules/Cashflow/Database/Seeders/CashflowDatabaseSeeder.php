<?php

namespace Modules\Cashflow\Database\Seeders;

use Illuminate\Database\Seeder;

class CashflowDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            CurrencySeeder::class,
            PaymentMethodSeeder::class,
            TransactionCategorySeeder::class,
            TermCategorySeeder::class,
            VendorSeeder​::class,
            ContractSeeder​::class,
            //RolesSeeder::class,
            UsersSeeder::class,
          ]);
    }
}
