<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cashflow_transactions';
	
	public function account()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\Account')->withDefault();
    }
	
	public function income_type()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\Category',"category_id")->withDefault();
    }
	
	public function expense_type()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\Category',"category_id")->withDefault();
    }
	
	// public function customer()
    // {
    //     return $this->belongsTo('App\Models\Customer',"customer_id")->withDefault();
    // }
	
    public function contract_term()
    {
        return $this->hasOne('Modules\Cashflow\App\Models\ContractTerm','id','contract_term_id')->with('term')->with('contract');
    }
	
	public function payment_method()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\PaymentMethod',"payment_method_id")->withDefault();
    }

    public function created_by()
    {
        return $this->belongsTo('App\Models\User',"created_user_id")->withDefault();
    }

    protected static function booted()
    {
        static::creating(function ($transaction) {
            $transaction->created_user_id = Auth::id();
        });

        static::updating(function ($transaction) {
            $transaction->updated_user_id = Auth::id();
        });
    }
	
    public function getPaidAtAttribute()
    {
        $date_format = get_date_format();
        return \Carbon\Carbon::parse($this->trans_date)->format("$date_format");
    }
}