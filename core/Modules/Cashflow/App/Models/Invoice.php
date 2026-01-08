<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    protected $connection = 'bodata_custom_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'E_INVOICE_HDR';
    protected $primaryKey = 'invoice_id';
    protected $appends = ['sum_amount_with_no_tax','sum_amount_with_tax', 'sum_quantity'];
    public $timestamps = false;
    public function invoiceDetails() {
        return $this->hasMany('Modules\Cashflow\App\Models\InvoiceDetail', 'invoice_id', 'invoice_id');
    }
    public function invoiceSales() {
        return $this->hasMany('Modules\Cashflow\App\Models\InvoiceSales', 'invoice_id', 'invoice_id');
    }
    public function branch() {
        return $this->belongsTo('Modules\Cashflow\App\Models\InvoiceBranch', 'branch_no', 'branch_no');
    }
    /**
     * Calculate and return the total amount for the invoice.
     *
     * @return float
     */
    public function getSumAmountWithNoTaxAttribute()
    {
        // Sum the 'amount' column from related InvoiceDetail records
        $total= $this->invoiceDetails->sum('amount_with_no_tax'); // Adjust the column name ('amount') as per your schema
        return decimalPlace($total);
    }
    /**
     * Calculate and return the total amount with tax for the invoice.
     *
     * @return float
     */
    public function getSumAmountWithTaxAttribute()
    {
        // Sum the calculated 'amount_with_tax' for each related InvoiceDetail record
        $total = $this->invoiceDetails->sum(function ($detail) {
            return $detail->amount_with_no_tax * (1 + $detail->taxPercentage / 100);
        });
        return decimalPlace($total);
    }
    /**
     * Calculate and return the total quantity for the invoice.
     *
     * @return float
     */
    public function getSumQuantityAttribute()
    {
        // Sum the 'amount' column from related InvoiceDetail records
        $total= $this->invoiceDetails->sum('quantity'); // Adjust the column name ('amount') as per your schema
        return $total;
    }
}