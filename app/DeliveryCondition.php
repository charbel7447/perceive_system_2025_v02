<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;
use App\PurchaseOrder\PurchaseOrder;
use App\Quotation\Quotation;
use App\SalesOrder\SalesOrder;

class DeliveryCondition extends Model
{
    protected $connection = "mysql";
    protected $table = 'deliverycondition';

    use Search;

    protected $search = [
        'name','created_by','created_at'
    ];

    protected $columns = [
        'name','created_by','created_at'
    ];

    protected $fillable = [
        'name','created_by','created_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getTextAttribute()
    {
        return $this->attributes['name'];
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

  
    public function items()
    {
        return $this->belongsTo(PurchaseOrder::class,'name','deliverycondition_id');
    }

    public function purchases()
    {
        return $this->hasMany(PurchaseOrder::class, 'name', 'deliverycondition_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'name', 'deliverycondition_id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'name', 'deliverycondition_id');
    }

    public function salesorders()
    {
        return $this->hasMany(SalesOrder::class, 'name', 'deliverycondition_id');
    }
    
    protected $appends = ['text'];
}
