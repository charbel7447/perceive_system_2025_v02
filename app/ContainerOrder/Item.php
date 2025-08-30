<?php

    namespace App\ContainerOrder;
    
    use Illuminate\Database\Eloquent\Model;
    use App\Product\Product;
    use App\Uom;
    use App\Vendor;
    use App\ContainerOrder\ContainerOrder;

    class Item extends Model
    {
        protected $table = 'container_order_items';
    
        protected $fillable = [
            'product_id', 'quantity','current_stock', 'unit_price', 'vendor_reference'
            ,'tax_name','tax_rate'
            ,'uom_id','uom2_id',
            'volume_box','ct_box','weight_box','shipper_id'
        ];
    
        public function product()
        {
            return $this->belongsTo(Product::class,'product_id','id');
        }
    
        public function containerOrder()
        {
            return $this->belongsTo(ContainerOrder::class, 'container_order_id', 'id');
        }
    
        // public function uom()
        // {
        //     return $this->hasMany(Uom::class, 'id','uom_id');
        // }
        
        public function taxes()
        {
            return $this->hasMany(Tax::class, 'purchase_order_item_id', 'id');
        }
    
        public function shipper()
        {
            return $this->belongsTo(Shipper::class, 'shipper_id','id');
        }
    
        public function uom()
        {
            return $this->belongsTo(Uom::class, 'uom_id','id');
        }
        public function uom2()
        {
            return $this->belongsTo(Uom::class, 'uom2_id','id');
        }
        
        public function productd()
        {
            return $this->belongsTo(Product::class,'product_id','id');
        }
        
    }
    