<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vendors';
    public function getTable()
    {
        return config('cashflow.database_prefix', '').parent::getTable();
    }
    public function getDisplayNameAttribute()
    {
        return $this->code.' - '.$this->name;
    }
    public function Contracts(){
        //return $this->hasMany('Modules\Cashflow\Models\Contract','vendor_id');
    }
}