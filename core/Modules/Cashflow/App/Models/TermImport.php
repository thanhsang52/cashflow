<?php
 
 namespace Modules\Cashflow\App\Models;
 
use App\Models\Term;
use Maatwebsite\Excel\Concerns\ToModel;
 
class TermImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Term([
            'code' => $row[0], 
            'name' => $row[1],
            //'contract_id' => 0, 
            'credit_acc_no' => !empty($row[3])?$row[3]:'', 
            'debit_acc_no' => !empty($row[4])?$row[4]:'', 
        ]);
    }
}