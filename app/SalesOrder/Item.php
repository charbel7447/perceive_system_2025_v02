<?php

namespace App\SalesOrder;

use App\FinishedProduct\FinishedProduct;
use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Uom;
use DB;

class Item extends Model
{
   // protected $table = 'sales_order_items';
    protected $connection = "mysql";
    protected $table = 'sales_order_items';

    protected $fillable = [
        'item_id', 'quantity', 'price','uom_id','uom_code','location','product_name','cost_price'
    ];

    public function product()
    {
        $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
        if($comany_type == 0){
            return $this->belongsTo(Product::class,'item_id','id');
        }else{
            return $this->belongsTo(FinishedProduct::class);
        }
    }

    public function product_location()
    {
        return $this->belongsTo(Product::class,'item_id','id')->orderBy('location','desc');
    }
    

    public function product_code ()
    {
        $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
        if($comany_type == 0){
            return $this->belongsTo(Product::class,'item_id','id');
        }else{
            return $this->belongsTo(FinishedProduct::class,'product_id','id');
        }
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class, 'sales_order_item_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id','id');
    }

    public function uomd()
    {
        return $this->belongsTo(Uom::class, 'uom','unit');
    }

    public function uom_unit()
    {
        return $this->belongsTo(Uom::class, 'uom','unit');
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
