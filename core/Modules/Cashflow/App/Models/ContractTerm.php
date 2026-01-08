<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractTerm extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cashflow_contract_term';
    /**
     * Indicates model primary keys.
     */
    //protected $primaryKey = ['contract_id', 'term_id'];
    protected $appends = ['display_name'];
    protected $fillable = ['contract_id','term_id','note','ref_num','ordering','billing_frequency','frequency_start_date','frequency_end_date','frequency_cycle','is_percentage','type','term_value'];
    public $timestamps = false;

    public function getDisplayNameAttribute()
    {
        return $this->contract->code.' | '.$this->term->display_name;
    }
    
    public function Contract()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\Contract',"contract_id")->withDefault();
    }
    public function Term()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\Term',"term_id")->withDefault();
    }
    public function Levels() {
        return $this->hasMany('Modules\Cashflow\App\Models\ContractTermLevel', 'contract_term_id');
    }
    public function Conditions() {
        return $this->hasMany('Modules\Cashflow\App\Models\ContractTermCondition', 'contract_term_id');
    }
    public function hasContract(): bool
    {
        return $this->Contract()->exists();
    }
}