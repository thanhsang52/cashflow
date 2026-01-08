<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ItemInfo extends Model
{
    protected $connection = 'bodata_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'POS_product_ext_info';
    protected $primaryKey = 'item_code';
    public $incrementing = false;

    public $timestamps = false;
    //protected $visible = ['description','unit_of_measure','stock_item_yn','discontinued','cat1','scat','sscat','cat_code'];
    //protected $appends = ['cat_code'];
    /*public function getCatCodeAttribute()
    {
        return $this->cat1.'-'.$this->scat.'-'.$this->sscat;
    }*/

    /*SELECT Item_Code as item_code, L.slug, Short_Description_VI as name_vi, Short_Description_EN as name_en, long_description_vi as description_vi, long_description_en as description_en ,images ,Product_Weight as weight, Product_Height as height, Product_Width as width , Product_Depth as depth,isnull( EXT.name_vi, '') as uom_vi
FROM MDC_APP.dbo.MDC_PRODUCT_LONG_DESC L
LEFT JOIN MDC_APP.dbo.MDC_PRODUCT_EXT EXT with(nolock) ON EXT.type='product_uom' and L.uom_id = EXT.id
WHERE Short_Description_VI is not NULL*/
}