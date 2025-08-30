<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;
use App\PurchaseOrder\Item as PurchaseOrderItem;
use App\PurchaseRequest\Item as PurchaseRequestItem;
use App\Currency;

class Employee extends Model
{
    use Search;

    protected $search = [
        'name', 'company', 'title','email', 'mobile_number',
        'extension','telephone'
  
    ];

    protected $columns = [
        'id',  'name', 'company', 'title','email', 'mobile_number',
        'extension','telephone', 'created_at','salary','currency_id'
    ];

    protected $fillable = [
        'name', 'company', 'title','email', 'mobile_number',
        'extension','telephone', 'created_at','salary','currency_id'
    ];

    protected $appends = [
        'text'
    ];

    protected $casts = [
        'vat_status'=> 'integer',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }
    

    public function purchase_order()
    {
        return $this->hasMany(PurchaseOrderItem::class,'employee_id','id');
    }

    public function purchase_request()
    {
        return $this->hasMany(PurchaseRequestItem::class,'employee_id','id');
    }

    public function payments()
    {
        return $this->hasMany(ClientPayment::class, 'client_id', 'id');
    }

    public function getTextAttribute()
    {
        if(is_null($this->attributes['company'])) {
            return $this->attributes['name'];
        }

        return $this->attributes['name'] .' - '. $this->attributes['company'];;
    }
}
