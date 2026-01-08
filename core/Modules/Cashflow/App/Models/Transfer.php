<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cashflow_transfers';


	public function expense()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\Transaction',"expense_transaction_id")->withDefault();
    }
	
	public function income()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\Transaction',"income_transaction_id")->withDefault();
    }
}