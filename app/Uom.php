<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;
use App\Items\Items;
use App\ReceiveOrder\ReceiveOrder;
use App\PurchaseOrder\PurchaseOrder;
use App\Quotation\Quotation;

class Uom extends Model
{
    protected $connection = "mysql";
    // protected $table = 'uom';
    protected $table = 'product_units';
    use Search;

    protected $search = [
        'unit','created_by','created_at'
    ];

    protected $columns = [
        'unit','created_by','created_at'
    ];

    protected $fillable = [
        'unit','created_by','created_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['unit'];
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function items()
    {
        return $this->belongsTo(Items::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
   
    // public function receiveoorder()
    // {
    //     return $this->belongsTo(ReceiveOrder::class,'unit','uom_id');
    // }


    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'name', 'id');
    }
    
    protected $appends = ['text'];
}
