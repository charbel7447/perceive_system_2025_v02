<?php

namespace App\Quotation;

use Illuminate\Database\Eloquent\Model;
use App\Product\Product;
use App\Uom;
use App\Client;
use App\FinishedProduct\FinishedProduct;
use App\FinishedProduct\Tax as FinishedProductTax;

use DB;

class Item extends Model
{
    protected $connection = "mysql";
    protected $table = 'quotation_items';

    protected $fillable = [
        'product_id', 'qty', 'unit_price','uom_id','uom_unit','uom_code','product_name',
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

        public function productd()
        {
            $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
            if($comany_type == 0){
                return $this->belongsTo(Product::class,'product_id','id');
            }else{
                return $this->belongsTo(FinishedProduct::class,'product_id','id');
            }
        }

        public function client()
        {
            return $this->belongsTo(Client::class,'user_id','id');
        }

        public function taxes()
        {
            $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
            if($comany_type == 0){
                return $this->hasMany(Tax::class, 'quotation_item_id', 'id');
            }else{
                return $this->hasMany(Tax::class, 'quotation_item_id', 'id');
            }
        }

        public function uom()
        {
            return $this->belongsTo(Uom::class, 'uom_id','id');
        }

        public function uomd()
        {
            $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
            if($comany_type == 0){
                return $this->belongsTo(Uom::class, 'uom_id','id');
            }else{
                return $this->belongsTo(Uom::class, 'uom_id','id');
            }
        }
    
}
