<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shift extends Model
{
    //protected $connection = 'bodata_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pos_shifts';
    protected $primaryKey = 'id';
    public $incrementing = true;

    public function user() {
        return $this->hasOne('App\Models\User','id','user_id');
    }
    
}