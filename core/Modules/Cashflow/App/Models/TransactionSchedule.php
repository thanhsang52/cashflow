<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransactionSchedule extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cashflow_transaction_schedule';
    protected $primaryKey = ['contract_term_id', 'transaction_date'];
    protected $fillable = ['contract_term_id','transaction_date','status'];
    public $timestamps = false;
    protected $dates = ['created_at'];
	
	
	public function ContractTerm()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\ContractTerm',"contract_term_id")->withDefault();
    }

    protected static function booted()
    {
        
    } 
}