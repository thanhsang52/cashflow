<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ItemMaster extends Model
{
    protected $connection = 'bodata_custom_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BODATA_Custom.Item_Master';
    protected $primaryKey = 'item_code';
    public $incrementing = false;

    public $timestamps = false;
    protected $visible = ['description','unit_of_measure','stock_item_yn','discontinued','cat1','scat','sscat','cat_code'];
    protected $appends = ['cat_code'];
    public function getCatCodeAttribute()
    {
        return $this->cat1.'-'.$this->scat.'-'.$this->sscat;
    }
}