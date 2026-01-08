<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InvoiceDetail extends Model
{
    protected $connection = 'bodata_custom_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'E_INVOICE_LINES';
    protected $primaryKey = 'id_invoice_lines';
    public $timestamps = false;
    public function invoice(){
    	return $this->belongsTo('Modules\Cashflow\App\Models\Invoice','id_invoice_lines','invoice_id')->withDefault();
    }
    
}