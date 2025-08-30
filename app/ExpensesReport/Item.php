<?php

namespace App\ExpensesReport;

use Illuminate\Database\Eloquent\Model;
use App\Uom;
use App\Vendor;
use App\Expense;

class Item extends Model
{
    protected $table = 'expenses_report_items';

    protected $fillable = [
        'user_id',
        'created_by',
        'from_date',
        'to_date',
        'vendor_id',
        'amount_paid',
        'amount_paid_lbp',
        'exchangerate',
        'payment_date',
    ];


    public function expenses()
    {
        return $this->belongsTo(Expense::class, 'expenses_id', 'id');
    }
    

    public function vendors()
    {
        return $this->belongsTo(Vendor::class,'vendor_id', 'id');
    }


}
