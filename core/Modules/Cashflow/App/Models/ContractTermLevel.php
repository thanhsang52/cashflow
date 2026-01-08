<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractTermLevel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cashflow_contract_term_level';
    /**
     * Indicates model primary keys.
     */
    //protected $primaryKey = ['contract_id', 'term_id'];
    //protected $appends = ['display_name'];
    //protected $fillable = ['contract_id','term_id','note','ref_num','ordering','billing_frequency','frequency_start_date','frequency_cycle'];
    public $timestamps = false;
    protected $dates = ['created_at'];

    // public function getDisplayNameAttribute()
    // {
    //     return $this->contract->code.' | '.$this->term->display_name;
    // }
    
    public function ContractTerm()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\ContractTerm',"contract_term_id")->withDefault();
    }
    
}