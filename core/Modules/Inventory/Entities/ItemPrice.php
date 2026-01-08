<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ItemPrice extends Model
{
    protected $connection = 'bodata_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ITEM_PRICE';
    protected $primaryKey = 'item_code';
    public $incrementing = false;

    public $timestamps = false;
    protected $visible = ['price'];

}