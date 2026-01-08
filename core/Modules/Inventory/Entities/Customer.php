<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    protected $connection = 'bodata_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CUSTOMER';
    protected $primaryKey = 'customer_code';
    public $incrementing = false;

    public $timestamps = false;
    protected $visible = ['customer_code','branch_no','first_name','last_name','dob'];
 

    
    
}