<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;
use App\Invoice\Invoice;
use App\Quotation\Quotation;
use App\SalesOrder\SalesOrder;
use App\ClientPayment\ClientPayment;
use App\AdvancePayment\AdvancePayment;
use App\ExchangeRate\ExchangeRate;
use App\Sellers\Sellers;

class Client extends Model
{
    use Search;

    protected $connection = "mysql";
    protected $table = 'clients';

    protected $search = [
        'name', 'company', 'email','id','ref_number','account_code'
    ];

    protected $columns = [
        'id', 'name', 'company', 'email', 'phone',
        'work_phone', 'billing_address', 'shipping_address','balance',
        'total_revenue', 'created_at','vat_status','zipcode','state','city','username','seller_id',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6',
        'field7',
        'field8','name_ar',
        'field9',
        'field10',
        'allow_mobile','ref_number','price_class','tax_id','deliverycondition_id','paymentcondition_id','deliverycondition_name','paymentcondition_name'
        ,'client_dropdown_1_id','client_dropdown_2_id'
    ];

    protected $fillable = [
        'name', 'company', 'email', 'phone',
        'work_phone', 'billing_address',
        'shipping_address',
        'currency_id','vat_status','zipcode','state','city','username','seller_id',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6','name_ar',
        'field7',
        'field8',
        'field9',
        'field10','allow_mobile','ref_number','price_class','tax_id','deliverycondition_id','paymentcondition_id'
        ,'deliverycondition_name','paymentcondition_name','client_dropdown_1_id','client_dropdown_2_id'
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

    public function exchangerate()
    {
        return $this->belongsTo(ExchangeRate::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id', 'id');
    }

    public function client_dropdown_1()
    {
        return $this->belongsTo(ClientDropDown1::class, 'client_dropdown_1_id','id');
    }

    public function client_dropdown_2()
    {
        return $this->belongsTo(ClientDropDown2::class, 'client_dropdown_2_id','id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'client_id', 'id');
    }


    public function deliverycondition()
    {
        return $this->belongsTo(DeliveryCondition::class, 'deliverycondition_id', 'id');
    }
    

    public function paymentcondition()
    {
        return $this->belongsTo(PaymentCondition::class, 'paymentcondition_id', 'id');
    }


    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'user_id', 'id');
    }

    public function advancePayments()
    {
        return $this->hasMany(AdvancePayment::class, 'client_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(ClientPayment::class, 'client_id', 'id');
    }

    public function creditnotes()
    {
        return $this->hasMany(\App\CreditNote\CreditNote::class, 'client_id', 'id');
    }

    

public function classes()
    {
        return $this->belongsTo(ClassesCode::class, 'account_id', 'code');
    }

    public function seller()
    {
        return $this->belongsTo(Sellers::class, 'seller_id', 'id');
    }


    public function getTextAttribute()
    {
        if(is_null($this->attributes['company'])) {
            return $this->attributes['name'];
        }
        
        elseif(is_null($this->attributes['name'])) {
            return $this->attributes['company'];
        }
    
        else{
            return $this->attributes['name'] .' - '. $this->attributes['company'];
        }
        
    }
}
