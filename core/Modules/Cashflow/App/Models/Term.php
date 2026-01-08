<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Term extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cashflow_terms';
    protected $fillable = ['code','name','credit_acc_no','dedit_acc_no'];
    protected $appends = ['display_name'];
    
    public function getDisplayNameAttribute()
    {
        return $this->code.' - '.$this->name;
    }
    public function category()
    {
        return $this->belongsTo('Modules\Cashflow\App\Models\TermCategory',"category_id")->withDefault();
    }
    public function contracts() {
        return $this->belongsToMany('Modules\Cashflow\App\Models\Contracts', 'contract_term', 'contract_id', 'term_id');
    }
    protected static function booted()
    {
        static::creating(function ($term) {
            $term->created_user_id = Auth::id();
        });

        static::updating(function ($term) {
            $term->updated_user_id = Auth::id();
        });
    }
}