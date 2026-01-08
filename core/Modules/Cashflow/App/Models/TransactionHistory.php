<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransactionHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cashflow_transaction_history';
	
	
	public function term()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\Transaction',"transaction_id")->withDefault();
    }

    protected static function booted()
    {
        static::creating(function ($history) {
            $history->created_user_id = Auth::id();
        });

        static::updating(function ($history) {
            $history->updated_user_id = Auth::id();
        });
    }
	
    
}