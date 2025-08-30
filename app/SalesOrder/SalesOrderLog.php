<?php

namespace App\SalesOrder;

use Illuminate\Database\Eloquent\Model;
use App\Support\HasManyRelation;
use App\Quotation\Quotation;
use App\Invoice\Invoice;
use App\Support\Search;
use App\Currency;
use App\Client;
use App\Delivery\Delivery;
use App\PaymentCondition;
use App\DeliveryCondition;
use App\Uom;
use DB;
use App\FinishedProduct\Item as FinishProductItem;
use App\Sellers\Sellers;

class SalesOrderLog extends Model
{
    use Search;
    use HasManyRelation;

    const DRAFT = 1;
    const SENT = 2;
    const CONFIRMED = 3;
    const HOLD = 4;
    const VOID = 5;
    const CLOSED = 6;
    const PARTIALLY_ISSUED = 7;
    const ISSUED = 8;

    // protected $connection = "mysql";
    // protected $table = 'sales_orders';
    protected $table = 'sales_orders_log';
    

    protected $search = [
        'reference', 'date', 'terms', 'number'
    ];

    protected $columns = [
        'id', 'number','client_id', 'reference', 'date', 'due_date','delivery_date','paymentcondition_id','deliverycondition_id','sub_total',
        'discount', 'total_amount', 'discount', 'shipping','terms', 'created_at','exchangerate','vatrate','vat_status','seller_id',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6',
        'field7',
        'field8',
        'field9',
        'field10','price_class','seller_commission',
        'line1_text','line1_value','line2_text','line2_value','line3_text','line3_value','line4_text','line4_value'
    ];

    protected $fillable = [
        'user_id','client_id', 'reference', 'currency_id', 'date','due_date','delivery_date','paymentcondition_id','deliverycondition_id',
        'discount', 'shipping','terms','exchangerate','vatrate','vat_status','total_amount','seller_id',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6',
        'field7',
        'field8',
        'field9',
        'field10','price_class','seller_commission',
        'line1_text','line1_value','line2_text','line2_value','line3_text','line3_value','line4_text','line4_value'
    ];

    protected $appends = ['is_editable','text'];

    protected $casts = [
        'status_id'=> 'integer',
    ];

    public function items()
    {
        $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
            if($comany_type == 0){
                return $this->hasMany(ItemLog::class,'order_id','id');
            }else{
                return $this->hasMany(ItemLog::class,'order_id','id');
            }
    }

    public function items_location()
    {
        return $this->hasMany(ItemLog::class,'order_id','id')->orderBy('location','asc');
    }
    

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    
    public function deliverycondition()
    {
        return $this->belongsTo(DeliveryCondition::class, 'deliverycondition_id', 'id');
    }

    public function paymentcondition()
    {
        return $this->belongsTo(PaymentCondition::class, 'paymentcondition_id', 'id');
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }

    public function seller()
    {
        return $this->belongsTo(Sellers::class,'seller_id','id');
    }

    public function clientd()
    {
        return $this->belongsTo(Client::class,'user_id','id');
    }
    
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'invoiceable');
    }

    public function uom()
    {
        return $this->hasMany(Uom::class, 'uom_id', 'id');
    }

    public function getIsEditableAttribute()
    {
        return in_array($this->attributes['status_id'], [1, 2]);
    }

  

    public function getTextAttribute()
    {
        return $this->attributes['number'];
    }
}
