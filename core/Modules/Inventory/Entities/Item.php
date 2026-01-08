<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    protected $connection = 'bodata_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Item';
    protected $primaryKey = 'item_code';
    public $incrementing = false;

    public $timestamps = false;
    protected $visible = ['description','unit_of_measure','stock_item_yn','discontinued','cat1','scat','sscat','cat_code','picture_filename'];
    protected $appends = ['cat_code'];
    public function getCatCodeAttribute()
    {
        return $this->cat1.'-'.$this->scat.'-'.$this->sscat;
    }
    public function product_price() {
        return $this->belongsTo('Modules\Inventory\Entities\ItemPrice', 'item_code', 'item_code')->withDefault();
    }
    public function ext_info() {
        return $this->hasOne('Modules\Inventory\Entities\ItemInfo','item_code','item_code');
    }
    
}