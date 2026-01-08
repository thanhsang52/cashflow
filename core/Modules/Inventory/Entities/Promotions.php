<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Promotions extends Model
{
    protected $connection = 'bodata_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'New_POS_Promotion';
    protected $primaryKey = ['promo_code','item_code'];
    public $incrementing = false;

    public $timestamps = false;
    protected $visible = ['start_date','end_date','promo_code','name','cat','price','promotion_price'];

}