<?php

namespace Modules\Cashflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Helper;
use Carbon\Carbon;
class Order extends Model
{
    protected $connection = 'website_mysql';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';
    protected $primaryKey = 'id';
    //public $timestamps = false;
    protected $casts = [
        'created_at'  => 'datetime:d/m/Y H:m:s',
        'total' => 'integer',
        'payment' => 'object',
        'shipping' => 'object',
    ];
    protected $appends = ['created_date'];
    public function getCreatedDateAttribute()
    {
        $format = config('smartend.date_format').' H:i';
        $new_str = new \DateTime($this->created_at, new \DateTimeZone(  'UTC'  ) );
        $new_str->setTimeZone(new \DateTimeZone('Asia/Ho_Chi_Minh'));
        return $new_str->format( $format);
    }
    public function orderLines() {
        return $this->hasMany('Modules\Cashflow\App\Models\OrderLine', 'order_id');
    }
    public function customer() {
        return $this->belongsTo('Modules\Cashflow\App\Models\WebsiteCustomer','customer_id')->withDefault();
    }
    public function address() {
        return $this->hasOne('Modules\Cashflow\App\Models\WebsiteAddress','id', 'address_id');
    }
}