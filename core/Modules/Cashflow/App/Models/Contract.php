<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Contract extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cashflow_contracts';
    protected $appends = ['display_name','display_effect_from','display_effect_to','display_contract_type'];
    public function getDisplayNameAttribute()
    {
        return $this->code.' - '.$this->name;
    }
    public function getDisplayEffectFromAttribute()
    {
        if(empty($this->effect_from)) return '';
        $date_format = get_date_format();
        return \Carbon\Carbon::parse($this->effect_from)->format("$date_format");
    }
    public function getDisplayEffectToAttribute()
    {
        if(empty($this->effect_to)) return '';
        $date_format = get_date_format();
        return \Carbon\Carbon::parse($this->effect_to)->format("$date_format");
    }
    
    public function getDisplayContractTypeAttribute()
    {
        $contract_types= get_option('contract_types' );
        if($contract_types) {
            $contract_types = unserialize($contract_types);
            if(isset($contract_types[$this->type])) return $contract_types[$this->type];
        }
        return $this->type;
    }
    // public function terms(){
    //   return $this->hasMany('App\Models\Term','contract_id');
    // }
    public function terms() {
        return $this->belongsToMany('Modules\Cashflow\App\Models\Term', 'cashflow_contract_term', 'contract_id', 'term_id')->withPivot('id','note','ref_num','ordering','billing_frequency','frequency_cycle','frequency_start_date','frequency_end_date','is_percentage','type','term_value');
    }
    public function contract_terms() {
        return $this->hasMany('Modules\Cashflow\App\Models\ContractTerm', 'contract_id','id');
    }
    public function vendor()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\Vendor','vendor_id');
    }
    
    public function cloneWithTerms()
    {
        $clone = $this->replicate();
        $clone->code.= "copy";
        $clone->push();

        foreach ($this->contract_terms as $contract_term) {
            //$clonedTask = $contract_term->replicate();
            //$clone->contract_terms()->save($clonedTask);
            $cloneTerms = $contract_term->attributes;
            $cloneTerms['contract_id'] = $clone->id;
            unset($cloneTerms['id']);
            
            $contractTermID = ContractTerm::insertGetId($cloneTerms,['contract_id','term_id']);

            $levels = $contract_term->Levels;
            if(isset($levels)){
                foreach($levels as $level){
                    $cloneLevels = $level->attributes;
                    $cloneLevels['contract_term_id'] = $contractTermID;
                    unset($cloneLevels['id']);
                    ContractTermLevel::upsert($cloneLevels,['contract_term_id','level']);
                }
            }
        }
        return $clone;
    }
    protected static function booted()
    {
        static::creating(function ($contract) {
            $contract->created_user_id = Auth::id();
        });

        static::updating(function ($contract) {
            $contract->updated_user_id = Auth::id();
        });
    }
}