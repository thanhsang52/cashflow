<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderLine extends Model
{
    protected $connection = 'website_mysql';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_lines';
    protected $primaryKey = 'id';
    //public $timestamps = false;
    public function order() {
        return $this->belongsTo('Modules\Cashflow\App\Models\Order', 'order_id')->withDefault();
    }
    
}