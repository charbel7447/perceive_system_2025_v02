<?php

namespace App;
use App\Support\Search;
use Illuminate\Database\Eloquent\Model;
use App\Items\Items;
use App\ReceiveOrder\ReceiveOrder;
use App\PurchaseOrder\PurchaseOrder;
use App\Quotation\Quotation;

class VatAccount extends Model
{
    protected $table = 'vat_accounts';

    use Search;

    protected $search = [
        'currency_id','name','name_ar','code'
    ];

    protected $columns = [
        'currency_id','name','name_ar','code'
    ];

    protected $fillable = [
        'currency_id','name','name_ar','code'
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


    
    protected $appends = ['text'];
}
