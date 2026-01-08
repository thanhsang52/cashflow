<?php
namespace Modules\Cashflow\Database\Seeders;
use Illuminate\Database\Seeder;
use DB;

class VendorSeeder​ extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = \DB::connection('bodata_sqlsrv')
                    ->table('SUPPLIER')
                    ->distinct()
                    ->select('SUPPLIER.supplier_code as code', 'category1 as cat', 'ADDRESS.name as name', 'ADDRESS.address3 as city', 'ADDRESS.address4 as country', 'ADDRESS.zipcode as zipcode', 'ADDRESS.e_mail as email', 'ADDRESS.phone as phone')
                    ->addSelect(DB::raw('CONCAT(ADDRESS.address1,\', \',ADDRESS.address2) as address'))
                    ->join('ADDRESS', 'ADDRESS.address_code', '=', 'SUPPLIER.address_code')
                    ->join('PORDER_HDR', 'PORDER_HDR.supplier_code', '=', 'SUPPLIER.supplier_code')
                    ->where('branch_yn',0)->whereIn('category2',["1_INV", "3_PXK"])->where('po_date','>', DB::raw('dateadd(year,-2, getDate())'))
                    ->get();
        foreach ($suppliers as $supplier) {
            DB::table('cashflow_vendors')->insert(
                [
                    'code'	        => $supplier->code,
                    'name'	        => $supplier->name,
                    'company_name'	=> $supplier->name,
                    'note'          => $supplier->code,
                    'vendor_cat'    => $supplier->cat,
                    'email'         => $supplier->email,
                    'phone'         => $supplier->phone,
                    'address'       => $supplier->address,
                    'city'          => $supplier->city,
                    'state'         => $supplier->city,
                    'country'       => $supplier->country,
                    'zip'           => $supplier->zipcode,
                    //'created_user_id'=>1,
                ]);
        }
       
    }
}
