<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;
use App\Bill\Bill;
use App\PurchaseOrder\PurchaseOrder;
use App\Expense\Expense;
use App\VendorPayment\VendorPayment;
use App\Product\Product;
use App\Product\Item as ProductItem;
use App\ReceiveOrder\ReceiveOrder;

class Vendor extends Model
{
    protected $connection = "mysql";
    use Search;
    
    protected $search = [
        'person', 'company', 'email', 'mobile_number', 'work_phone',
        'billing_address', 'shipping_address', 'payment_details','vat_status','discount','account_code'
    ];

    protected $columns = [
        'id', 'person', 'company', 'email', 'mobile_number',
        'work_phone', 'billing_address', 'shipping_address', 'payment_details',
        'total_revenue', 'created_at','vat_number','vat_status','discount','shipping_process','name_ar'
    ];

    protected $fillable = [
        'person', 'company', 'email', 'mobile_number', 'work_phone',
        'billing_address', 'shipping_address', 'payment_details',
        'currency_id','vat_number','vat_status','discount','shipping_process','name_ar'
    ];

    protected $appends = [
        'text'
    ];
    protected $casts = [
        'vat_status'=> 'integer',
    ];
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function receiveOrders()
    {
        return $this->hasMany(ReceiveOrder::class);
    }

    public function classes()
    {
        return $this->belongsTo(ClassesCode::class, 'account_id', 'code');
    }
    
    public function payments()
    {
        return $this->hasMany(VendorPayment::class);
    }

    public function products()
    {
        return $this->hasMany(ProductItem::class, 'vendor_id', 'id');
    }

    public function uom()
    {
        return $this->hasMany(Uom::class,'id', 'uom_id');
    }
    
    public function getTextAttribute()
    {
        if(is_null($this->attributes['company'])) {
            return $this->attributes['person'];
        }

        return $this->attributes['person'] .' - '. $this->attributes['company'];;
    }

}
