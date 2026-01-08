<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TenderType extends Model
{
    protected $connection = 'bodata_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'TENDER_TYPE';
    protected $primaryKey = 'tender_code';
    public $incrementing = false;

    public $timestamps = false;
    protected $visible = ['tender_code','tender_desc','min_tender_value','max_tender_value','enable_yn'];
 

    
    
}