<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WebsiteAddress extends Model
{
    protected $connection = 'website_mysql';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'addresses';
    //protected $primaryKey = 'id';
    //protected $appends = ['full_name'];
    /*public function getFullNameAttribute()
    {
        return $this->family_name.' '.$this->given_name;
    }*/
    public function order() {
        return $this->belongsTo('Modules\Cashflow\App\Models\Order', 'address_id')->withDefault();
    }
    
}