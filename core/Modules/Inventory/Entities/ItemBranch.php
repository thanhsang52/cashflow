<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ItemBranch extends Model
{
    protected $connection = 'bodata_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ITEM_BRANCH';
    protected $primaryKey = ['branch_no', 'item_code'];
    public $incrementing = false;

    public $timestamps = false;
    protected $appends = ['combined_key','price','item_arname','supplier_codes'];
    protected $casts = [
        'on_hand' => 'integer',
        'on_order' => 'integer',
        'discontinued' => 'boolean',
        'available_yn' => 'boolean',
    ];
    public function getCombinedKeyAttribute()
    {
        return $this->branch_no.'_'.$this->item_code;
    }
    public function getPriceAttribute()
    {
        return number_format($this->product_price->price, 0);
    }
    public function getItemArnameAttribute()
    {
        return isset($this->product->ext_info->name_vi)?$this->product->ext_info->name_vi:$this->product->description;
    }
    public function getSupplierCodesAttribute()
    {
        $codes = '';
        foreach($this->suppliers as $supplier){
            $codes.=$supplier->supplier_code. ' ';
        }
        return $codes;
    }
    public function product_price() {
        return $this->belongsTo('Modules\Inventory\Entities\ItemPrice', 'item_code', 'item_code')->withDefault();
    }
    public function product() {
        return $this->belongsTo('Modules\Inventory\Entities\Item', 'item_code', 'item_code')->withDefault();
    }
    public function suppliers() {
        return $this->hasMany('Modules\Inventory\Entities\SupplierItem', 'item_code', 'item_code')->where('pref_supp_no','>', 0);
    }
    public function promotions() {
        return $this->hasMany('Modules\Inventory\Entities\Promotions', 'cat', 'item_code')->where('start_date','<=',Carbon::now())->where('end_date','>=',Carbon::now());
    }
    public function hasPromotion(): bool
    {
        return $this->promotions()->exists();
    }
}