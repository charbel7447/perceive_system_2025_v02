<?php

namespace App\Invoice;

use App\FinishedProduct\FinishedProduct;
use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Uom;
use DB;

class Item extends Model
{
    protected $table = 'invoice_items';

    protected $fillable = [
        'product_id', 'qty', 'unit_price','uom_id','qty_on_hand','uom_unit'
    ];

    public function product()
    {
        $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
            if($comany_type == 0){
                return $this->belongsTo(Product::class);
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
        return $this->hasMany(Uom::class, 'id','uom_id');
    }

    public function uomd()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }
    public function productd()
    {
        $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
            if($comany_type == 0){
                return $this->belongsTo(Product::class,'product_id','id');
            }else{
                return $this->belongsTo(FinishedProduct::class,'product_id','id');
            }
            
    }
}
