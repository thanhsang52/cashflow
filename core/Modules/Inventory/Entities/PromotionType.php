<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PromotionType extends Model
{
    protected $connection = 'bodata_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'PROMOTION_Type';
    protected $primaryKey = ['type'];
    public $incrementing = false;

    public $timestamps = false;
    
}