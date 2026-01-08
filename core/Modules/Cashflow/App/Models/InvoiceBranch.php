<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InvoiceBranch extends Model
{
    protected $connection = 'bodata_custom_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'E_INVOICE_TAX_BRANCH';
    protected $primaryKey = 'branch_no';
    public $timestamps = false;
    public function invoices(){
    	return $this->hasMany('Modules\Cashflow\App\Models\Invoice','branch_no','branch_no')->withDefault();
    }
    
}