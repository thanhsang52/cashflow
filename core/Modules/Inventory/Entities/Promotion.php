<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
class Promotion extends Model
{
    protected $connection = 'bodata_sqlsrv';  // This tells Laravel to use the SQL Server connection
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'PROMOTION';
    protected $primaryKey = 'promo_code';
    public $incrementing = false;

    public $timestamps = false;
    /**
    * @var array
    */
    protected $discountTypes = [
        0=>'Absolute price',
        1=>'Discount percent',
        2=>'Unit discount amount'
    ];
    /**
    * @var array
    */
    protected $promotionTypes = [
        'VO'=>'Value off affter quantity reached',
        'LD'=>'LD Lowest priced item discounted once quantity reached',
        'FP'=>'FP Fixed price per item once quantity reached',
        'TD'=>'Sale Total Discount',
        'GP'=>'GP Pay fixed priced for a group of items',
        'GD'=>'GD Buy A Group Of Items Get A Group Discounted',
        'ST'=>'Sale Total dollar discount',
        'PO'=>'PO Percent off affter quantity reached'
    ];
    protected $appends = ['promotion_type_name','discount_type_name','qualifying_quantity','num_items_discount','is_member_price'];
    //protected $visible = ['start_date','end_date','promo_code','name','cat','price','promotion_price'];
    public function promotionItems() {
        return $this->hasMany('Modules\Inventory\Entities\PromoItem','promo_code','promo_code');
    }
    public function levels() {
        return $this->hasMany('Modules\Inventory\Entities\PromoLevel','promo_code','promo_code');
    }
    public function promotionType() {
        return $this->belongsTo('Modules\Inventory\Entities\PromotionType','type','type');
    }
    public function items() {
        return $this->belongsToMany('Modules\Inventory\Entities\Item', 'PROMO_ITEM', 'promo_code', 'cat')->withPivot('bin');
    }
    public function hasItem(): bool
    {
        return $this->items()->exists();
    }
    public function hasLevel(): bool
    {
        return $this->levels()->exists();
    }
    protected $casts = [
        'start_date'  => 'datetime:d/m/Y H:i:s',
        'end_date' => 'datetime:d/m/Y H:i:s',
        'last_modified' => 'datetime:d/m/Y H:i:s',
    ];
    public function getPromotionTypeNameAttribute()
    {
        return Arr::get($this->promotionTypes, $this->type);
    }
    /**
    * @param int $value
    * @return string|null
    */
    public function getDiscountTypeNameAttribute()
    {
        return Arr::get($this->discountTypes, $this->discount_type);
    }
    public function getQualifyingQuantityAttribute()
    {
        switch ($this->type){
            /*case 'GP': 
                $rs = [];
                for($i=1;$i<6;$i++)
                if($this->{'bin_count'.$i}>0){
                    $bin="bin".$i;
                    $rs[]=$bin.":".(int)$this->{'bin_count'.$i};
                }
                //return 'bin1:'.(int)$this->bin_count1.'|bin2:'.(int)$this->bin_count1;
                return implode("|",$rs);*/
            case 'FP': 
            case 'GP':
            case 'PO': 
            case 'LD':    
            case 'GD':    
                    return array('id'=>$this->promo_code,'type'=>$this->type, 'name' => $this->name);
            default:
                $field_name = 'bin_count1';
                return (int)$this->$field_name;

        }
        

        //return $this->$field_name;
    }
    public function getNumItemsDiscountAttribute()
    { 
        if($this->type!='GP' && $this->type!='FP')
            return $this->discounted_bin;
        else
            return "_";
    }
    public function getIsMemberPriceAttribute()
    { 
        if($this->condition_type=='DM' && $this->condition_value==45)
            return true;
        return false;
    }
}