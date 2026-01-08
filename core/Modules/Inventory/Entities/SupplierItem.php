<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SupplierItem extends Model
{
    protected $connection = 'bodata_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'SUPPLIER_ITEM';
    protected $primaryKey = ['item_code','supplier_code'];
    public $incrementing = false;

    public $timestamps = false;
    protected $visible = ['supplier_code','item_code','supplier_cost','orderdate'];
    
}