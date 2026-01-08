<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class ContractTermCondition extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cashflow_contract_term_condition';
    /**
     * Indicates model primary keys.
     */
    //protected $primaryKey = ['id'];
    //protected $appends = ['display_name'];
    protected $fillable = ['contract_term_id','attributes','discount','created_by'];
    public $timestamps = false;
    //protected $dates = ['created_at'];

    // public function getDisplayNameAttribute()
    // {
    //     return $this->contract->code.' | '.$this->term->display_name;
    // }
    
    public function ContractTerm()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\ContractTerm',"contract_term_id")->withDefault();
    }
    protected static function booted()
    {
        static::creating(function ($condition) {
            $condition->created_by = Auth::id();
        });

        
    }
}