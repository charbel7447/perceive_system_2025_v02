<?php

namespace App\Invoice;

use App\FinishedProduct\FinishedProduct;
use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Uom;
use DB;

class ItemLog extends Model
{
    protected $connection = "mysql";
    protected $table = 'invoice_items_log';

    protected $fillable = [
        'item_id', 'quantity', 'price','product_name',
        //  ,'uom_unit','uom',
       'uom_code','discount_usd','discount_per'
    ];
    // ,'qty_on_hand'

    public function product()
    {
        $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
            if($comany_type == 0){
                return $this->belongsTo(Product::class,'item_id','id');
            }else{
                return $this->belongsTo(FinishedProduct::class);
            }
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class, 'invoice_item_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_code','name');
    }

    public function uomd()
    {
        // return $this->belongsTo(Uom::class, 'name','uom_code');
        return $this->belongsTo(Uom::class, 'uom_code','name');
    }
    public function productd()
    {
        $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
            if($comany_type == 0){
                return $this->belongsTo(Product::class,'item_id','id');
            }else{
                return $this->belongsTo(FinishedProduct::class,'item_id','id');
            }
            
    }
}
