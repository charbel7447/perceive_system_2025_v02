<?php

namespace App\Expense;

use Illuminate\Database\Eloquent\Model;
use App\Support\Search;
use App\Support\HasManyRelation;
use App\Currency;
use App\Vendor;
use App\Bill\Bill;

class Expense extends Model
{
    use HasManyRelation;
    use Search;

    protected $search = [
        'id', ' number', 'payment_date', 'amount_paid', 'amount_paid_lbp','description','exchangerate'
    ];

    protected $columns = [
        'id', 'number', 'payment_date', 'amount_paid', 'amount_paid_lbp','description',
        'created_at','exchangerate','vat_status',
          'bill_number','bill_date','bill_total','bill_id'
    ];

    protected $fillable = [
        'vendor_id', 'description', 'currency_id', 'payment_date',
        'amount_paid','amount_paid_lbp','exchangerate','vat_status',
          'bill_number','bill_date','bill_total','bill_id'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

     public function bill()
    {
        return $this->belongsTo(Bill::class,'bill_id','id');
    }

    

     public function items()
    {
        return $this->hasMany(Item::class, 'expense_id', 'id');
    }

     public function items2()
    {
        return $this->hasMany(Item2::class, 'expense_id', 'id');
    }
}
